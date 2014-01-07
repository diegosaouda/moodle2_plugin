<?php

//defined('MOODLE_INTERNAL') || die();
define("CLI_SCRIPT",true); //remover após termino de codificação: execução em modo manual
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib/cron.php');
require_once($CFG->dirroot . '/local/lib/course.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');  

$config = get_config('notification_quiz_cron_time_execute');

if((time() - $config->lastcron) <  $CFG->notification_quiz_cron_time_execute) return 0;

//html com informações para o admin
$html_admin_notification = '';

$config = array(
    //'time'                      => $CFG->notification_quiz_cron_time_execute,
    'days'                      => config_filter_days($CFG->notification_quiz_day),
    'courses'                   => $CFG->notification_quiz_courses,
    'subject'                   => $CFG->notification_email_subject,
    'message'                   => $CFG->notification_email_message,
    'admin_mail_notification'   => explode(';', preg_replace('/\s/','', $CFG->notification_email_users)),
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
    $dias_encerrar = (int) round( ($quiz->timeclose - time()) / 60 / 60 / 24, 0) ; 
    
    //atividade não está na data certa da notificação
    if (! in_array($dias_encerrar, $config['days'])) continue;
    
    //pegar nome do curso
    $course = $DB->get_record('course', array('id' => $quiz->course), 'id, fullname');
    
    //pegar alunos do curso
    $context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
    $context_in = get_related_contexts_string($context);

    $users = students_by_course(5,$context_in);
    
    $html_admin_notification.= '<br /><strong>'.$course->fullname.'</strong><ul>';
    
    //verificar se aluno já participou do quiz
    foreach ($users as $user) {
        
        $attempt = $DB->get_record('quiz_attempts', array('quiz' => $quiz->id, 'userid' => $user->id));
        
        $attempts = quiz_get_user_attempts($quiz->id, $user->id);
        if (count($attempts) !== 0) {
            //verificar se foi tudo respondido 
            continue; // por enquanto só vai notificar que não teve tentativa
       }
       
       //notificar alunos
       $html_admin_notification.= '<li>'.$user->firstname.' '.$user->lastname.'</li>';
       mail_to($user->email, $config['subject'], $config['message']);
    }
    
    $html_admin_notification.= '</ul>';
    
    //notificar administradores
    foreach($config['admin_mail_notification'] as $mail) {
        mail_to($mail, $config['subject'], $html_admin_notification);
    }
}



//ultima execução do cron
set_config('lastcron', time(), 'notification_quiz_cron_time_execute');


function mail_to($email, $subject, $message)
{
    global $CFG;
    
    $mail = get_mailer();
    $mail->From     = $CFG->noreplyaddress;
    //$mail->FromName = $from;
    $mail->Subject  = substr($subject, 0, 900);
    $mail->isHTML(true);
    $mail->Encoding = 'quoted-printable';
    $mail->Body    =  $message;
    $mail->AltBody =  "\n".strip_tags($message)."\n";
    
    $mail->addAddress($email);
    return $mail->send();
}



