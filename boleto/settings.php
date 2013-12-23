<?php

defined('MOODLE_INTERNAL') || die;


//adicionando menu

$ADMIN->add('root', new admin_category('local_boleto', 'Boleto'));

$ADMIN->add('local_boleto', new admin_externalpage('admin', 'Envio de boleto',
        $CFG->wwwroot."/local/boleto/admin.php",
        'local/boleto:upload'));

$ADMIN->add('local_boleto', new admin_externalpage('view', 'Gerenciar boletos',
        $CFG->wwwroot."/local/boleto/view.php",
        'local/boleto:upload'));

$ADMIN->add('local_boleto', new admin_externalpage('my', 'Meus Boletos',
        $CFG->wwwroot."/local/boleto/my.php"));


/*
$ADMIN->add('local_hub', new admin_externalpage('managesites', 'managesites',
        $CFG->wwwroot."/local/hub/admin/managesites.php",
        'moodle/site:config'));

$ADMIN->add('local_hub', new admin_externalpage('managecourses', 'managecourses',
        $CFG->wwwroot."/local/hub/admin/managecourses.php",
        'moodle/site:config'));

$ADMIN->add('local_hub', new admin_externalpage('hubregistration', 'register',
        $CFG->wwwroot."/local/hub/admin/register.php",
        'moodle/site:config'));

$ADMIN->add('local_hub', new admin_externalpage('registrationconfirmed',
        'confirmregistration',
        $CFG->wwwroot."/local/hub/admin/confirmregistration.php",
        'moodle/site:config', true));

$ADMIN->add('local_hub', new admin_externalpage('sitesettings', 'sitesettings',
        $CFG->wwwroot."/local/hub/admin/sitesettings.php",
        'moodle/site:config', true));

$ADMIN->add('local_hub', new admin_externalpage('hubcoursesettings', 'coursesettings',
        $CFG->wwwroot."/local/hub/admin/coursesettings.php",
        'moodle/site:config', true));

$ADMIN->add('local_hub', new admin_externalpage('hubstolensecret', 'stolensecret',
        $CFG->wwwroot."/local/hub/admin/stolensecret.php",
        'moodle/site:config'));
 
 * 
 */