<?php

defined('MOODLE_INTERNAL') || die();
function xmldb_local_participation_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();


    // Moodle v2.3.0 release upgrade line
    // Put any upgrade step following this


    return true;
}
