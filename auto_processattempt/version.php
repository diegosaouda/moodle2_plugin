<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2012092106;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2012061700;        // Requires this Moodle version
$plugin->component = 'local_auto_processattempt';  // Full name of the plugin (used for diagnostics)
$plugin->cron      = 60;