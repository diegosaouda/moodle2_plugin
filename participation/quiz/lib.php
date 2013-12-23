<?php

function quiz_slots($questions){
    $questions = explode(',0',$questions);
    $questions = array_shift($questions);
    
    $questions = explode(',',$questions);
    
    if(count($questions)<=0){
        throw new moodle_exception('Ocorreu um erro, o quiz está inválido');
    }
    
    return $questions;
}

function participation_quiz_download($results, $questions_id, $course){
    
    $producao = 1;
    
    if($producao){
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=notas");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }
    
    $workbook = new Workbook("");
    
    
    
    $worksheet1 = & $workbook->add_worksheet("notas");
    //$worksheet1->setInputEncoding('utf-8');
    
    $worksheet1->set_column(1, 1, 40);
    $worksheet1->set_row(1, 20);
    
    
    $worksheet1->write_string(0, 1, utf8_decode($course->fullname));
    $worksheet1->write_string(1, 1, "Nome");
    $worksheet1->write_string(1, 2, "Turma");
    $worksheet1->write_string(1, 3, utf8_decode("Avaliação"));
    
    $coluna = 2;
    foreach($questions_id as $key => $question){
        $coluna ++;
        
        $worksheet1->write_string(2, $coluna, "#" . $key);        
        
        
        unset($question);
    }
    
    $worksheet1->write_string(2, $coluna+1, utf8_decode("Média"));
    
    $linha = 2;
    foreach($results as $result){
        
        $linha++;
        $coluna = 0;
                
        if(!isset($result['sumgrades_attempts'])){
            $worksheet1->write_string($linha, $coluna, '#');
        }        
        
        $worksheet1->write_string($linha, ++$coluna, utf8_decode($result['user']->firstname . ' ' . $result['user']->lastname));
        $worksheet1->write_string($linha, ++$coluna, utf8_decode($result['user']->group_name));
        
        
        foreach($questions_id as $number => $question_id){            
            if(!isset($result['sumgrades_attempts'][$number+1])){
                $result['sumgrades_attempts'][$number+1] = 0;
            }            
            $worksheet1->write_number($linha, ++$coluna, $result['sumgrades_attempts'][$number+1]);            
        }
        
        if(!isset($result['sumgrades'])){
            $result['sumgrades'] = 0;
        }
        
        $worksheet1->write_number($linha, ++$coluna, $result['sumgrades']);
    }
    
    $linha+=3;    
    $worksheet1->write_string($linha, 1, '# => aluno sem tentativa');
    
    $workbook->close();
    
}

function attempt_higher($attempts) {
    
    if (count($attempts) === 1) {
        return array_shift($attempts);
    }
    
    $tmp = 0;
    $tmp_id = 0;
    foreach ($attempts as $attempt_id => $attempt) {
        
        if (!is_numeric($attempt->sumgrades)) {
            continue;
        }
        
        if ($tmp <= $attempt->sumgrades) {
            $tmp = $attempt->sumgrades;
            $tmp_id = $attempt_id;
        }
    }
    
    return $attempts[$tmp_id];
}


function attempt_lower($attempts) {
    
    if (count($attempts) === 1) {
        return array_shift($attempts);
    }
    
    $tmp = 9999;
    $tmp_id = 0;
    foreach ($attempts as $attempt_id => $attempt) {
        
        if (!is_numeric($attempt->sumgrades)) {
            continue;
        }
        
        if ($tmp >= $attempt->sumgrades) {
            $tmp = $attempt->sumgrades;
            $tmp_id = $attempt_id;
        }
    }
    return $attempts[$tmp_id];
}