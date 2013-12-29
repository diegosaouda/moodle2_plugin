<?php

//time cron quiz
function get_time_execute() 
{
    return new admin_setting_configtext(
        'notification_quiz_cron_time_execute', 
        'Cron Quiz', 
        'Frequência que o cron deverá ser executado, tempo em segundos', '43200', PARAM_INT
    );
}

//dias notificação
function get_quiz_day() 
{
    return new admin_setting_configtext(
        'notification_quiz_day', 
        'Dias', 
        'Quantos dias antes da atividade encerrar que o aluno deve ser notificado. Separe por vírgula para adicionar mais dias', '5,15', PARAM_SEQUENCE
    );
}

//cursos que devem ser notificados
function get_quiz_courses() 
{
    $choices = list_choice_courses();
    return new admin_setting_configmulticheckbox(
        'notification_quiz_courses', 
        'Cursos', 
        'Selecione os cursos que irão receber notificação de encerramento da QUIZ', '', $choices
    );
}

//assunto de notificação
function get_notification_subject()
{
    return new admin_setting_configtext(
        'notification_email_subject', 
        'E-mail Assunto', 
        'Informe o assunto do e-mail de notificação', '', PARAM_TEXT
    );
}

//mensagem de notificação
function get_notification_message()
{
    $tip = '';
    $tip .= 'Informe a mensagem do e-mail de notificação. Algumas palavras chaves podem ser usadas, como: <br />';
    $tip .= '<br /><b>{user.firstname}</b> : Primeiro nome do usuário';
    $tip .= '<br /><b>{user.lastname}</b> : Último nome do usuário';
    $tip .= '<br /><b>{course.name}</b> : Nome do curso';
    $tip .= '<br /><b>{quiz.name}</b> : Nome da atividade "QUIZ"';
    $tip .= '<br />As palavras chaves serão substituidas de acordo com a necessidade de quem será informado';
    
    
    return new admin_setting_confightmleditor(
        'notification_email_message', 
        'E-mail Mensagem', 
        $tip, '', PARAM_TEXT
    );
}


/**
 * Retorna a lista de cursos disponívei
 * @return array
 */
function list_choice_courses()
{
    global $DB;
    
    $sql = "select id, fullname from {course} where format <> 'site' and visible = 1";    
    $courses = $DB->get_records_sql($sql);
    
    $choice = array();
    foreach ($courses as $course) {
        $choice[$course->id] = $course->fullname;
    }
    
    return $choice;
}