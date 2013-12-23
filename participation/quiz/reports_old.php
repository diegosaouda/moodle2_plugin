<?php

/* 11426
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    require_once(dirname(__FILE__) . '/../../../config.php');  
    require_once($CFG->dirroot . '/mod/quiz/locallib.php'); 
    
    
    $attemptobj = quiz_attempt::create(11426);
    
    
    //$attemptobj->get_question_attempt($slot);
    
    var_dump($attemptobj->get_slots());