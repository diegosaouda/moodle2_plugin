<?php

require_once(dirname(__FILE__) . '/../../config.php');

require_login();

$contents = file_get_contents('SRS_JC2_errado_remover.txt');

$line = explode("\r\n",$contents);

$shortname = array_shift($line);
$course = $DB->get_record('course', array('shortname' => $shortname), '*', MUST_EXIST);

$enrol = $DB->get_record('enrol', array('enrol' => 'manual', 'courseid' => $course->id), '*', MUST_EXIST);

$plugin = enrol_get_plugin($enrol->enrol);

foreach($line as $username){
  $username = trim($username);
  
  $user = $DB->get_record('user', array('username' => $username), '*');
  
  if(!$user){
    var_dump($user);
    var_dump($username);
    continue;
  }
  
  //$user_enrolments = $DB->get_record('user_enrolments', array('userid' => $user->id, 'enrolid' => $enrol->id), '*', MUST_EXIST);
  
  $plugin->unenrol_user($enrol, $user->id);  
}

