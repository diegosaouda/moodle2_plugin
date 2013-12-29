<?php
require_once('lib/settings.php');

if ($hassiteconfig) {
    $settings = new admin_settingpage('notification', 'Notificação');
    $settings->add(get_time_execute());
    $settings->add(get_quiz_day());
    $settings->add(get_quiz_courses());
    $settings->add(get_notification_subject());
    $settings->add(get_notification_message());
    
    $ADMIN->add('localplugins', $settings);
}