<?php

    require_once(dirname(__FILE__) . '/../../../config.php');
    
    require_login();
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/participation_quiz:view', $systemcontext)){
      throw new moodle_exception('Você não tem permissão para acessar esse recurso');      
    }
    
    $course_id = required_param('course',PARAM_INT);
    
    //dados do curso
    $course = $DB->get_record('course', array('id' => $course_id), '*', MUST_EXIST);
    
    //lista que questionário do curso
    $quiz_list = $DB->get_records_select('quiz', 'course = ?', array($course->id),'id asc','id,name');
    
    //$allroles = get_all_roles();
    
    
    //informações de layout
    $PAGE->set_url('/local/participation/quiz/view.php');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_context(null);             
            
    //titulo e heading (titulo em h2)
    $PAGE->set_title('Atividades');
    $PAGE->set_heading('Atividades');
    
    $PAGE->navbar->add('Participação');
    $PAGE->navbar->add('Quiz');
    $PAGE->navbar->add('Atividades');
       
        
    echo $OUTPUT->header();
?>
  
<div id="addadmisform">
       
    <h3 class="main"><?php echo $course->fullname; ?></h3>
    
    <div class="boxaligncenter" style="width: 300px; text-align: center;">
                
        <p>Selecione um questionário desse curso</p>
        
        <form action="<?php echo $PAGE->url . '/../reports.php'; ?>" method="GET">
        
          <input type="hidden" value="<?php echo $course->id; ?>" name="course" />
          
          <select name="quiz" style="padding: 6px;">            
            <option value="0"> -- Selecione o curso desejado -- </option>
            
            <?php foreach($quiz_list as $list): ?>
              <option value="<?php echo $list->id; ?>"><?php echo $list->name; ?></option>
            <?php endforeach; ?>
                                    
          </select>
          
          <br /><br /><br />
          
          <p>Selecione a tentativa que deseja visualizar</p>
          
          <select name="attempt" style="padding: 6px;">            
            <option value="0"> Primeira tentativa </option>
            <option value="1"> Ultima tentativa </option>
            <option value="2" selected="selected"> Tentativa mais alta </option>
            <option value="3"> Tentativa mais baixa </option>
          </select>
          
          
          <br /><br />
          Deseja realizar download ?<br />
          <input type="checkbox" name="download" checked="checked" value="1" />
           Se sim, selecione o campo ao lado
          
          <br /><br />
          <input type="submit" value="Gerar Relatório" style="padding: 6px;" />
          
        </form>                
        
    </div>    
    
</div>

<?php echo $OUTPUT->footer(); ?>
