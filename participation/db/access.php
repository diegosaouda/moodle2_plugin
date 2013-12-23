<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    /* This is used only when sync suspends users instead of full unenrolment */
    'local/participation_quiz:view' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        )
    ),
);


