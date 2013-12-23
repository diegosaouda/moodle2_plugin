<?php

require_once(dirname(__FILE__) . '/../../config.php');

$path_boleto = $CFG->dataroot . DIRECTORY_SEPARATOR . 'boleto';

$meses = range(1, 12);
$mes = optional_param('mes', 0, PARAM_INT);

$json = array('success' => true);

try {
            
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);        
    if(!has_capability('local/boleto:upload', $systemcontext)){
      throw new Exception('Você não tem permissão para acessar esse recurso');      
    }
        
    if(!in_array($mes, $meses)){
        throw new Exception('Mês não é válido');
    }
    
    if(count($_FILES)<=0){
        throw new Exception('Nenhum arquivo foi recebido');
    }
            
    $file = $_FILES['Filedata'];
    
    if((int)$file['error'] > 0){
        throw new Exception('Ocorreu um erro no processo de upload: ' . $file['erro']);
    }
        
    if(!preg_match('/^[0-9]{11}\.pdf$/', $file['name'])){
        throw new Exception('arquivo não foi nomeado de forma correta');
    }
            
    $username = (float)$file['name'];            
    $user = $DB->get_record('user', array('username'=>$username), 'id, username');
    
    if(!$user){
        throw new Exception('usuário não encontrado');
    }
    
    $path_boleto .= DIRECTORY_SEPARATOR . date('Y'); 
    $path_boleto .= DIRECTORY_SEPARATOR . str_pad($mes,2,'0',STR_PAD_LEFT);
    
    if(!is_dir($path_boleto)){
        if(!@mkdir($path_boleto, 0770, true)){
            throw new Exception('não é possível criar o diretório');
        }
    }
    
    $path_boleto .= DIRECTORY_SEPARATOR . $file['name'];
    
    if(!@copy($file['tmp_name'], $path_boleto)){
        throw new Exception('não é possível gravar o boleto');
    }    
    
}
catch(Exception $e){
    $json = array('message' => $e->getMessage(),'success' => false);
}

$json['server'] = $_SERVER;

echo json_encode($json);