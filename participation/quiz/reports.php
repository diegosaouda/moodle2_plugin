<?php

    define('TENTATIVA_PRIMEIRA' , 0);
    define('TENTATIVA_ULTIMA'   , 1);
    define('TENTATIVA_ALTA'     , 2);
    define('TENTATIVA_BAIXA'    , 3);
    
    require_once(dirname(__FILE__) . '/../../../config.php');
    require_once($CFG->dirroot . '/local/lib/course.php');
    require_once($CFG->dirroot . '/mod/quiz/locallib.php');  
    require_once($CFG->dirroot . '/lib/excel/Worksheet.php');
    require_once($CFG->dirroot . '/lib/excel/Workbook.php');
    
    require_once('lib.php');

    require_login();
    
    $cont_user_sem_grupo = 0;
    $cont_user_sem_tentativa = 0;
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/participation_quiz:view', $systemcontext)){
    throw new moodle_exception('Você não tem permissão para acessar esse recurso');      
    }


    $course_id = required_param('course',PARAM_INT);
    $quiz_id   = required_param('quiz',PARAM_INT);
    $download  = optional_param('download',0,PARAM_BOOL);
    $optionAttempt   = optional_param('attempt',0,PARAM_INT);
    
    //dados do curso
    $course = $DB->get_record('course', array('id' => $course_id), '*', MUST_EXIST);

    //context
    $context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
    $context_in = get_related_contexts_string($context);

    $users = students_by_course(5,$context_in);
            
    //dados do quiz
    $quiz = $DB->get_record('quiz', array('id' => $quiz_id, 'course' => $course_id), '*', MUST_EXIST);
        
    //$PAGE->set_url('/local/participation/quiz/reports.php');
    //$PAGE->set_pagelayout(null);
    //$PAGE->set_context(null);  
    
    $questions_id = quiz_slots($quiz->questions);
            
    //preparando os dados    
    $results = array();   
    
    try {
    
        foreach($users as $userid => $user){
            
            $results[$userid]['user'] = $user;
                        
            $attempts = quiz_get_user_attempts($quiz->id, $userid);
            
            //sem tentativas
            if( !$attempts ) continue;
            
            switch ($optionAttempt) {
            case TENTATIVA_PRIMEIRA: 
                $attempts = array_shift($attempts);    
                break;
            case TENTATIVA_ULTIMA:
                $attempts = array_pop($attempts);
                break;
            case TENTATIVA_ALTA:
                $attempts = attempt_higher($attempts);
                break;
            case TENTATIVA_BAIXA: 
                $attempts = attempt_lower($attempts);
                break;    
            }
                        
            $attemptobj = quiz_attempt::create($attempts->id);
            unset($attempts);
                                    
            foreach($attemptobj->get_slots() as $slots){
                $nota = $attemptobj->get_question_attempt($slots)->get_mark() * ($quiz->grade / $quiz->sumgrades);  
                $results[$userid]['sumgrades_attempts'][$slots] = (number_format($nota, $quiz->decimalpoints));
                unset($slots, $nota);
            }        

            $nota = $attemptobj->get_attempt()->sumgrades * ($quiz->grade / $quiz->sumgrades);        
            $results[$userid]['sumgrades'] = number_format($nota, $quiz->decimalpoints);
            unset($attemptobj, $nota);
        }
        
    }
    
    catch (Exception $e) {
        echo $e->getMessage();
    }
    
    //download ?
    if($download){
        participation_quiz_download($results, $questions_id, $course);
        return ;
    }
    
?>

<!doctype html>
<head>
    <meta charset="utf-8" />
    <style type="text/css">
        html, body {
            font-size: 14px;
            font-family: arial;
        }
        table {border-spacing: 1px;}
        table td {
            padding: 4px;
             border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    
    <div style="text-align: center;"> <a href="courses.php">Novo relatório</a></div>
    
    <div id="addadmisform">        
        <div id="container">
            <h2><?php echo $course->fullname; ?> (<?php echo $course->shortname; ?>)</h2>

            <table style="width: 100%;">

                <tr style="background-color: #eee;">
                    <td style="width: 300px;" rowspan="2">Nome / Sobrenome</td>                
                    <td style="width: 100px;" rowspan="2">Turma</td>
                    <td style="text-align: center;" colspan="<?php echo count($questions_id)+1; ?>">Atividades</td>
                </tr>

                <tr style="background-color: #eee;">                
                    <?php foreach($questions_id as $key => $question_id): ?>
                    <td style="text-align: center; width: 30px;">#<?php echo ($key+1); ?></td>  
                    <?php endforeach; ?>
                    <td style="text-align: center; width: 50px;">Média</td>
                </tr>

                <?php foreach($results as $result): ?>

                    <tr>
                        <td>
                            <?php echo $result['user']->firstname . ' ' . $result['user']->lastname ; ?>
                        </td>

                        <td>
                            <?php if (!$result['user']->group_name): ?>
                            <?php $cont_user_sem_grupo++; ?>    
                            <span title="sem grupo">[...]</span>
                            <?php else: ?>
                            <?php echo $result['user']->group_name; ?>
                            <?php endif; ?>
                        </td>
                            <!-- sum grades attempts -->                        
                            <?php if (isset($result['sumgrades_attempts'])): ?>                        
                                <?php foreach($result['sumgrades_attempts'] as $sumgrades_attempts): ?>                          
                                    <td style="text-align: center;">
                                    <?php echo $sumgrades_attempts; ?>
                                    </td>
                                <?php endforeach; ?> 

                                <!-- sum grades -->    
                                <td style="text-align: center;">
                                    <?php echo $result['sumgrades']; ?>
                                </td>    

                            <?php else: ?>  
                                <td colspan="<?php echo count($questions_id); ?>">
                                <?php $cont_user_sem_tentativa++; ?>    
                                <span title="sem tentativa">[...]</span>
                                </td>
                                <td style="text-align: center;">0</td>
                            <?php endif; ?>                        


                    </tr>

                <?php endforeach; ?>

            </table>

            <br />
            <strong>Estatísticas do resultado</strong>
            <p>Alunos sem grupo: <?php echo $cont_user_sem_grupo; ?></p>
            <p>Alunos sem tentativas: <?php echo $cont_user_sem_tentativa; ?></p>
            <p>Total de alunos: <?php echo count($results); ?></p>

        </div>    
        
    </div>
</body>    