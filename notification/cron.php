<?php

//defined('MOODLE_INTERNAL') || die();
define("CLI_SCRIPT",true); //remover após termino de codificação: execução em modo manual
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib/cron.php');

$config = array(
    'time'      => $CFG->notification_quiz_cron_time_execute,
    'days'       => config_filter_days($CFG->notification_quiz_day),
    'courses'   => $CFG->notification_quiz_courses,
    'subject'   => $CFG->notification_email_subject,
    'message'   => $CFG->notification_email_message,
); 

$hoje = time();

$sql = "select id, course, name, timeopen, timeclose
        from {quiz}
	where timeclose <> 0
            and course IN ({$config['courses']})
            and {$hoje} >= timeopen
            order by course, id, timeclose asc";
            
$quizs = $DB->get_records_sql($sql);

foreach ($quizs as $quiz) {
    //descobrindo quanto tempo falta para encerrar uma atividade
    $dias_encerrar = round( ($quiz->timeclose - time()) / 60 / 60 / 24, 0) ; 
    
    //atividade não está na data certa da notificação
    if (! in_array($dias_encerrar, $config['days'])) continue;
    
    //pegar nome do curso
    
    //pegar alunos do curso
    
    //pegar alunos que não entregaram atividade
}





