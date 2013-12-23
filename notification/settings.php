<?php

if ($hassiteconfig) {
    
    $settings = new admin_settingpage('notification', 'Notificação');
    
    //time cron quiz
    $settings->add(new admin_setting_configtext(
        'notification_quiz_cron_time_execute', 
        'Cron Quiz', 
        'Frequência que o cron deverá ser executado, tempo em segundos', '43200', PARAM_INT
    ));
    
    //dias notificação
    $settings->add(new admin_setting_configtext(
        'notification_quiz_day', 
        'Dias', 
        'Quantos dias antes da atividade encerrar que o aluno deve ser notificado. Separe por vírgula para adicionar mais dias', '5,15', PARAM_SEQUENCE
    ));
    
    //cursos que devem ser notificados
    $settings->add(new admin_setting_configtext(
        'notification_quiz_courses', 
        'Cursos', 
        'Informe o ID dos cursos que devem ser notificados. Separe por vírgula para mais de um curso', '', PARAM_SEQUENCE
    ));
    
    $ADMIN->add('localplugins', $settings);
}
