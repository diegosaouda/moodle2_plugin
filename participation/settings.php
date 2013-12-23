<?php

defined('MOODLE_INTERNAL') || die;


//adicionando menu

$ADMIN->add('root', new admin_category('local_participation', 'Participação'));

$ADMIN->add('local_participation', new admin_category('quiz', 'Quiz'));

$ADMIN->add('quiz', new admin_externalpage('admin', 'Selecionar o curso',
        $CFG->wwwroot."/local/participation/quiz/courses.php",
        'local/participation_quiz:view'));

/*$ADMIN->add('quiz', new admin_externalpage('admin', 'Notificação',
        $CFG->wwwroot."/local/participation/quiz_notification/.php",
        'local/quiz_notification:view'));*/