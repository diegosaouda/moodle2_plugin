<?php

    require_once(dirname(__FILE__) . '/../../config.php');
    
    require_login();
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/boleto:upload', $systemcontext)){
      throw new moodle_exception('Você não tem permissão para acessar esse recurso');      
    }
    
    //boletos para visualização
    $boletos = array();
    
    $view = optional_param('view', 0, PARAM_BOOL);
            
    $path_boleto = $CFG->dataroot . DIRECTORY_SEPARATOR . 'boleto' . DIRECTORY_SEPARATOR;
    
    //anos disponíveis
    $path_ano = glob($path_boleto.'*');
    
    $anos_validos = array_map(function($ano){
        return basename($ano);
    },$path_ano);
    
    $meses_validos = range(1,12);
    
      
    $boleto_ano = optional_param('boleto_ano', 0, PARAM_INT);
    $boleto_mes = optional_param('boleto_mes', 0, PARAM_INT);
    
    if($boleto_ano > 0){
      if(!in_array($boleto_ano, $anos_validos)){
        throw new moodle_exception('Ano não é válido');
      }  
      
      $path_boleto .= $boleto_ano . DIRECTORY_SEPARATOR;
      
    }
    
    if($boleto_mes > 0){      
      if(!in_array($boleto_mes, $meses_validos)){
        throw new moodle_exception('Mês não é válido');
      }      
      
      $boletos = glob($path_boleto . str_pad($boleto_mes,2,0,STR_PAD_LEFT) . DIRECTORY_SEPARATOR . '*');                  
    }
    
    //  
    //$boletos = glob($path_boleto . '*');
    //var_dump($boletos);
    
    $PAGE->set_url('/local/boleto/view.php');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_context(null);
        
    //titulo e heading (titulo em h2)
    $PAGE->set_title("Visualização de boletos");
    $PAGE->set_heading("Visualização de boletos");
        
            
    echo $OUTPUT->header();
?>
  
<div id="addadmisform">
  
  <h3 class="main">Boletos</h3>

  <p>Selecione o ano clique em visualizar. Depois selecione um mês.</p> 
  
  <form action="<?php echo $PAGE->url; ?>" method="GET">
        
    <select name="boleto_ano" style="padding: 6px;">
      <option value="0">Ano</option>
      
      <?php foreach($anos_validos as $ano): ?>
      
        <?php 
          $selected = '';
          $selected = $boleto_ano == $ano ? 'selected="selected"' : '';
        ?>
      
        <option value="<?php echo $ano; ?>" <?php echo $selected; ?>><?php echo $ano; ?></option>
      
      <?php endforeach; ?>
      
    </select>
    
    
    
    <?php if ($boleto_ano): ?>
    
      <select name="boleto_mes" style="padding: 6px;">
        <option value="0">Mês</option>
        
        <?php foreach($meses_validos as $mes): ?>
        
          <?php 
            $selected = '';
            $selected = $boleto_mes == $mes ? 'selected="selected"' : '';
          ?>
        
          <option value="<?php echo $mes; ?>" <?php echo $selected; ?>>

            <?php echo '('; ?>
            <?php echo count(glob($path_boleto . str_pad($mes,2,0,STR_PAD_LEFT) . DIRECTORY_SEPARATOR . '*')); ?>
            <?php echo ')'; ?>
          
            <?php echo userdate(mktime(0,0,0,$mes,1,0),'%B'); ?>                                    
          </option>
        
        <?php endforeach; ?>
      </select>
      
    <?php endif; ?>
    
    <input type="submit" value="Visualizar" style="padding: 6px;" />
    
  </form>    
  
  <?php if ($boletos): ?>

        
    <h3 class="main">Mês <?php echo userdate(mktime(0,0,0,$boleto_mes,1,0),'%B'); ?></h3>
  
    <table class="generaltable boxaligncenter" style="width: 90%">
      <tr>
        <th class="header c0">Username</th>
        <th class="header c1" style="width: 100px;">#</th>
        <th class="header c2" style="width: 100px;">#</th>
      </tr>
      <?php foreach($boletos as $boleto): ?>
      
      <tr>
        <td class="cell c0" style="text-align: center;"><?php echo basename($boleto); ?></td>
        
        <td class="cell c1" style="text-align: center;">
          <a href="<?php echo $PAGE->url;?>/../mod.php?option=download&mes=<?php echo $boleto_mes; ?>&ano=<?php echo $boleto_ano; ?>&file=<?php echo basename($boleto); ?>">download</a>
        </td>
        
        <td class="cell c2" style="text-align: center;">
          <a href="<?php echo $PAGE->url;?>/../mod.php?option=remove&mes=<?php echo $boleto_mes; ?>&ano=<?php echo $boleto_ano; ?>&file=<?php echo basename($boleto); ?>">remover</a>
        </td>
      </tr>
      
      <?php endforeach; ?>
    </table>
  
  <?php endif; ?>
  
</div>

<?php echo $OUTPUT->footer(); ?>