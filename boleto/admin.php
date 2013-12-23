<?php

    require_once(dirname(__FILE__) . '/../../config.php');
    
    require_login();
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/boleto:upload', $systemcontext)){
      throw new moodle_exception('Você não tem permissão para acessar esse recurso');      
    }
                        
    $PAGE->requires->css('/local/lib/swfuplod/default.css');                
    
    $PAGE->requires->js('/local/lib/swfuplod/swfupload.js');
    $PAGE->requires->js('/local/lib/swfuplod/swfupload.queue.js');
    $PAGE->requires->js('/local/lib/swfuplod/fileprogress.js');
    $PAGE->requires->js('/local/lib/swfuplod/handlers.js');    
    $PAGE->requires->js('/local/boleto/upload.js');    
    
    $PAGE->set_url('/local/boleto/admin.php');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_context(null);
        
    //titulo e heading (titulo em h2)
    $PAGE->set_title("Boleto Admin");
    $PAGE->set_heading("Boleto Admin");
        
    
    
    echo $OUTPUT->header();
?>

<div id="addadmisform">
            
    <h3 class="main">Boletos</h3>

    <p>Nessa página você poderá fazer upload de boletos.</p>
    <p>Para que o boleto esteja visível para o aluno, o mesmo deverá estar nomeado com o número do cpf do aluno mais a extensão .pdf .</p>
    
    <br />
    <strong>Selecione o mês, escolha os boletos e clique no botão iniciar envio</strong>
    
    <table border="0">
        <tr>
            <td>
                <select id="upload_mes" style="padding: 6px;">
                    <option value="0">Selecione o mês do boleto</option>
                    <?php foreach(range(1,12) as $mes): ?>
                    <option value="<?php echo $mes; ?>"> <?php echo userdate(mktime(0,0,0,$mes,1,0),'%B'); ?> </option>
                    <?php endforeach; ?>
                </select>                
            </td>
    
            <td>
                <div>
                    <span id="swfupload_select">Selecionar boletos</span>
                </div>
            </td>
        </tr>
    </table>
            
    <div style="height: 300px; overflow: auto; border: 2px dotted #ccc;">        
        <div id="swfupload_queue">            
        </div>        
    </div>
    
    <br />
    
    <button style="padding: 6px;" id="swfupload_begin" disabled="disabled" onclick="swf_upload_upload_begin()" href="#">Iniciar envio</button>
    <button style="padding: 6px;" id="swfupload_cancel" disabled href="#">Parar envio</button>

    <a href="<?php echo $PAGE->url; ?>">[Zerar Filas]</a>

    <br /> <br /> <br />

    <div>
        <h3>Status do Envio</h3>
        <ul>            
            <li><span id="swfupload_status_total">0</span> Total de arquivos</li>
            <li><span id="swfupload_status_remainder">0</span> Arquivo(s) restante(s)</li>
            <li><span id="swfupload_status_success">0</span> Arquivo(s) com sucesso</li>
            <li><span id="swfupload_status_error">0</span> Erro(s) contrado(s)</li>
        </ul>
    </div>
        
    
</div>

<?php echo $OUTPUT->footer(); ?>