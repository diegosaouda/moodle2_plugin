<?php

    require_once(dirname(__FILE__) . '/../../config.php');
    
    require_login();
    
    $download = optional_param('download', 0, PARAM_BOOL);
    $mes = optional_param('mes', 0, PARAM_INT);
	$ano = optional_param('ano', date('Y'), PARAM_INT);
    
    $path_boleto = $CFG->dataroot . "/boleto/{$ano}/";
	
	$dates = array_map(function($value) {
		return basename($value);
	}, glob(dirname($path_boleto).'/*'));
	
    if($download){
        
        $path_boleto .= str_pad($mes,2,0,STR_PAD_LEFT) . DIRECTORY_SEPARATOR . $USER->username . '.pdf';
        
        if(!file_exists($path_boleto)){
            throw new moodle_exception('Boleto não foi encontrado');
        }
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($path_boleto));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path_boleto));
        ob_clean();
        flush();
        readfile($path_boleto);
        
        return ;
    }
    
    
    $PAGE->set_url('/local/boleto/my.php');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_context(null);
        
    //titulo e heading (titulo em h2)
    $PAGE->set_title("Boletos - ano " . $ano);
    $PAGE->set_heading("Boletos - ano " . $ano);
            
    echo $OUTPUT->header();
?>
  
<div id="addadmisform">
        
    
    <h3 class="main">Meus Boletos</h3>    
		
	<p style="text-align:center;">
		<select onchange="if (this.value !=='') { window.location.href='<?php echo $PAGE->url; ?>?ano=' + this.value ; }">
			<option>Selecionar outro ano</option>
			
			<?php foreach($dates as $date): ?>
				<option value="<?php echo $date ?>"><?php echo $date ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	
	
    <p style="text-align:center;">Esse espaço é destinado a impressão de boletos</p>                

    <table class="generaltable boxaligncenter" style="width: 300px;">
        <tr>
            <th class="header c0">Mês</th>
            <th class="header c1" style="width: 100px;">Status</th>        
        </tr>
        
        <?php foreach(range(1,12) as $mes): ?>

        <tr>
            <td class="cell c0" style="text-align: center;"><?php echo userdate(mktime(0,0,0,$mes,1,0),'%B'); ?></td>
            <td class="cell c1" style="text-align: center;">
                <?php if(file_exists($path_boleto . str_pad($mes,2,0,STR_PAD_LEFT) . DIRECTORY_SEPARATOR . $USER->username . '.pdf')): ?>
                    <a href="<?php echo $PAGE->url; ?>?download=true&mes=<?php echo $mes; ?>"> download </a>
                <?php else: ?>
                    não disponível
                <?php endif; ?>    

            </td>
        </tr>    

        <?php endforeach; ?>
    </table>
        
    
    
</div>

<?php echo $OUTPUT->footer(); ?>