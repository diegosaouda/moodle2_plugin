<?php

if($hassiteconfig){
    if ($hassiteconfig) {
        
        $settings = new admin_settingpage('local_auto_processattempt', 'Envio automático de questionário');
    
        $ADMIN->add('localplugins', $settings);

        $settings->add(new admin_setting_configtext(
            'local_auto_processattempt_cron', 'Execução do Cron', 'Frequência que o cron deverá ser executado, tempo em segundos', '43200', PARAM_INT)
        );
        
    }
}

//$settings = new admin_settingpage('local_pluginname', 'display name');