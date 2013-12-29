<?php

//defined('MOODLE_INTERNAL') || die();
define("CLI_SCRIPT",true); //remover após termino de codificação: execução em modo manual
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib/cron.php');

$config = array(
    'time'      => $CFG->notification_quiz_cron_time_execute,
    'day'       => config_filter_day($CFG->notification_quiz_day),
    'courses'   => explode(',', $CFG->notification_quiz_courses),
    'subject'   => $CFG->notification_email_subject,
    'message'   => $CFG->notification_email_message,
); 

//iniciando processamento course / course
foreach($config['courses'] as $course_id) {
    
    
}