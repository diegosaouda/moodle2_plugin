<?php

    require_once(dirname(__FILE__) . '/../../../../config.php');
    require_once($CFG->dirroot . '/local/lib/course.php');        
    require_once($CFG->dirroot . '/mod/quiz/locallib.php');        
    
    $cont_sem_tentativas = 0;
    $cont_alunos = 0;
    
    //params
    $module_id = required_param('module',PARAM_INT);
    
    $module = $DB->get_record('course_modules', array('id' => $module_id), '*', MUST_EXIST);
    $course_id = $module->course;
    $quiz_id = $module->instance;
    unset($module);
    //$course_id = required_param('course',PARAM_INT);
    //$quiz_id   = required_param('quiz',PARAM_INT);
    
    $group_id  = optional_param('group', 0,PARAM_INT);
    
    //informações do layout
    $PAGE->set_url('/local/reports/participation/quiz/view.php');    
    $PAGE->set_context(null);
    
    
    //dados do curso
    $course = $DB->get_record('course', array('id' => $course_id), '*', MUST_EXIST);
    
    //dados do quiz
    $quiz = $DB->get_record('quiz', array('id' => $quiz_id, 'course' => $course_id), '*', MUST_EXIST);
    
    $context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
    $context_in = get_related_contexts_string($context);
    
    $users = array();
    if($group_id){
      //obtem somente os usuários do grupo $group_id
      $users = students_by_course(5,$context_in,$group_id,''); 
      $group = $DB->get_record('groups', array('id' => $group_id), '*', MUST_EXIST);
    }
    else {
      //obtem todos os usuários do curso
      $users = students_by_course(5,$context_in,0,'');      
    }
    
    
    //preparando os dados    
    $results = array();   
    
    try {
    
        foreach($users as $userid => $user){
            $cont_alunos++;
            $results[$userid]['user'] = $user;                        
            $attempts = quiz_get_user_attempts($quiz->id, $userid,'all');
                                    
            if(!$attempts){  
              $cont_sem_tentativas++;
              continue;
            }
            
            foreach($attempts as $attempt){              
                $attemptobj = quiz_attempt::create($attempt->id);

                $results[$userid]['attempts'][$attempt->attempt]['detail'] = array(
                  'id' => $attempt->id                  
                );                
                
                $results[$userid]['attempts'][$attempt->attempt]['status'] = array(                    
                    'string'     => get_string('state'.$attemptobj->get_state(),'quiz'),
                    'status'     => $attemptobj->get_state()
                );
                foreach($attemptobj->get_slots() as $slots){                  
                  $results[$userid]['attempts'][$attempt->attempt]['slots'][$slots] = array(
                      'string' => get_string($attemptobj->get_question_attempt($slots)->get_state()->get_state_class(''),'question'),
                      'status' => $attemptobj->get_question_attempt($slots)->get_state_class('')
                  );
                }                
                
                unset($attempt);               
            }            
            unset($attempts);            
        }        
    }
    
    catch(Exception $e){        
        echo $e->getMessage();
    }            
        
    echo $OUTPUT->header();
?>
  
<div id="addadmisform">
    
    <a href="https://sites.google.com/site/neadrelatoriosfia/equipe-de-gestao-2/monitoramento">Retornar ao Menu</a> <br /><br />

    <strong>Curso: <?php echo $course->fullname; ?></strong>
    <br />    
    
    <?php if(isset($group)): ?>
        <strong>Grupo: <?php echo $group->name; ?></strong>
    <?php endif; ?>
    
    <?php foreach($results as $result): ?>
    
        <h1><?php echo $result['user']->firstname . ' ' . $result['user']->lastname; ?></h1>
        
        <?php 
            //Alunos sem tentativa
            if(!isset($result['attempts'])) {                
                echo '<div style="background-color: #111; padding: 2px; color: #fff;">';
                echo 'Sem tentativa';
                echo '</div>';
                echo '<br />';
                echo '<hr />';
                echo '<br />';
                continue;                
            }
        ?>
        
        <?php foreach($result['attempts'] as $key=>$attemps): ?>
        
            <?php echo 'Tentativa: ' . $key; ?>
            (<?php echo $attemps['status']['string']; ?>)
            &nbsp;<a href="<?php echo new moodle_url('/mod/quiz/review.php?attempt=' . $attemps['detail']['id']); ?>"> [visualizar respostas] </a>
                                    
            <br />

            
            <?php foreach($attemps['slots'] as $attempt=>$attempt_status): ?>
            
                <?php                                 
                    switch($attempt_status['status']){
                        case 'complete': 
                        case 'answersaved':    
                            $color = '#A6EDA6';
                            break;
                        default:
                            $color = '#F7B2B2';
                            break;
                    }                    
                ?>
            
                <table border="1" style="float: left; width: 80px; height: 86px; border: 1px solid #ccc; background-color: <?php echo $color; ?>;">
                    <tr>                    
                        <td style="text-align: center;" valign="top">
                            #<?php echo $attempt; ?>
                        </td>
                    </tr>    
                    <tr>                    
                        <td style="text-align: center;">                            
                            <?php echo $attempt_status['string']; ?>
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?>    
            <div style="clear: left;"></div>
                                    
        <?php endforeach; ?>
        <hr /><br />
    
    <?php endforeach; ?>
        
</div>

<div>
    Total de alunos: <?php echo $cont_alunos; ?>
    <br />Total que não tentaram responder o questionário: <?php echo $cont_sem_tentativas; ?>
</div>

<div style="padding: 2px; text-align: center;">
    Tentativa: Em progresso = Envio automático ainda não foi realizado
    <br />
    Tentativa: Finalizadas = Envio automático realizado
</div>

<?php echo $OUTPUT->footer(); ?>
