<?php

    require_once(dirname(__FILE__) . '/../../config.php');
    
    require_login();
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/boleto:upload', $systemcontext)){
      throw new moodle_exception('Você não tem permissão para acessar esse recurso');      
    }
            
    $option   = required_param('option' , PARAM_ALPHA  );
    $mes      = required_param('mes'    , PARAM_INT     );
    $ano      = required_param('ano'    , PARAM_INT     );
    $file     = required_param('file'   , PARAM_RAW  );
    
    $path_boleto = $CFG->dataroot . DIRECTORY_SEPARATOR; 
    $path_boleto .= 'boleto' . DIRECTORY_SEPARATOR;
    $path_boleto .= $ano . DIRECTORY_SEPARATOR;
    $path_boleto .= str_pad($mes,2,0,STR_PAD_LEFT) . DIRECTORY_SEPARATOR;
    $path_boleto .= $file;
        
    if(!file_exists($path_boleto)){
      throw new moodle_exception('Arquivo não existe');
    }
    
    switch($option){
      default: throw new moodle_exception('Opção não é válida'); break;
      
      case 'download':
        
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
        
        break;
        
        
      case 'remove': 
        
        if(!@unlink($path_boleto)){
          throw new moodle_exception('Não foi possível remover o boleto');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
        return ;
                
        break;        
      
    }
    