<?php

function config_filter_days($day_raw)
{   
    //transformando em array
    $day_array = explode(',', $day_raw);
    
    //filtrando valores diferentes de false (0,'',null,array()) 
    $day_valid = array_filter($day_array);
    
    //removendo valores repetidos
    $day_uniq = array_unique($day_valid);
    sort($day_uniq);
    
    return $day_uniq;
}
