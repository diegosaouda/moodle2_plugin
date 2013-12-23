<?php

define("CLI_SCRIPT",true);

/**
 * Envio das respostas dos usuários de forma automatica
 * depois que o questionário foi finalizado
 */


require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

$houve_erro = false;
$unix_timestamp = time();


$sql = "select id, course, name, timeopen, timeclose
        from {quiz}
	where 
	    timeclose <> 0
            and $unix_timestamp > timeclose
            and ($unix_timestamp - (86400 * 2)) < timeclose    
        order by course, id, timeclose asc";

$quiz_finalizado = $DB->get_records_sql($sql);

mtrace("");
mtrace("");

mtrace("Quiz - Envio automatico");

if(!$quiz_finalizado){
    mtrace("nada a ser feito");    
}

else {

    foreach($quiz_finalizado as $quiz){

        $cont_ok = 0;
        $cont_error = 0;
        $cont_tentativas = 0;

        mtrace("quiz: " . $quiz->id);

        $sql = "select id from {quiz_attempts} where quiz = ? and timefinish = 0";    
        $tentativas_nao_finalizadas = $DB->get_records_sql($sql, array($quiz->id));

        //nenhuma tentativa não finalizada encontrada
        if(!$tentativas_nao_finalizadas){
            mtrace("    nao existe tentativas nao finalizadas");
            continue;
        }

        foreach($tentativas_nao_finalizadas as $tentativa){

            $cont_tentativas++;

            $attemptobj = quiz_attempt::create($tentativa->id);

            mtrace("    attemptid: " . $tentativa->id             , "");
            mtrace("    userid: "    . $attemptobj->get_userid()  , "");

            try {
                $attemptobj->process_finish($unix_timestamp, false);

                add_to_log(
                    $attemptobj->get_courseid(), 
                    'quiz', 
                    'close attempt', 
                    'review.php?attempt=' . $attemptobj->get_attemptid(),
                    $attemptobj->get_quizid(), 
                    $attemptobj->get_cmid(), 
                    $attemptobj->get_userid()
                );

                $cont_ok++;
                mtrace(" [OK]");            
            }
            catch (Exception $e){

                $cont_error++;
                $houve_erro = true;

                mtrace(" " . $e->getMessage());            
            }
        }

        mtrace("");
        mtrace("    ok: "         . $cont_ok          , "");
        mtrace("    erros: "      . $cont_error       , "");
        mtrace("    tentativas: " . $cont_tentativas  , "");
        mtrace("");

        mtrace("==============================================","\n\n");    
    }
}

//futuramente enviar e-mail
if($houve_erro){
	echo "\n\nHouve Erro\n\n";    
}
