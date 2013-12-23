<?php

    require_once(dirname(__FILE__) . '/../../../config.php');
    
    require_login();
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/participation_quiz:view', $systemcontext)){
      throw new moodle_exception('Você não tem permissão para acessar esse recurso');      
    }
    
    
    $PAGE->set_url('/local/participation/quiz/courses.php');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_context(null);
            
    //titulo e heading (titulo em h2)
    $PAGE->set_title('Participação');
    $PAGE->set_heading('Participação');
    
    $courses_list = $DB->get_records_select('course', 'category <> 0', array(),'fullname asc','id,fullname,shortname');
    
    echo $OUTPUT->header();
?>
  
<div id="addadmisform">
        
    <h3 class="main">Selecione o curso que deseja analisar</h3>
    
    <div class="boxaligncenter" style="width: 300px; text-align: center;">
        <form action="<?php echo $PAGE->url . '/../view.php'; ?>" method="GET">
            <select name="course" style="padding: 6px;">            
            <option value="0"> -- Selecione o curso desejado -- </option>

            <?php foreach($courses_list as $list): ?>
                <option value="<?php echo $list->id; ?>"><?php echo $list->fullname . ' ('.$list->shortname.')'; ?></option>
            <?php endforeach; ?>


            </select>

            <br /><br />
            <input type="submit" value="Próximo passo" style="padding: 6px;" />

        </form>
    </div>

</div>

<?php echo $OUTPUT->footer(); ?>