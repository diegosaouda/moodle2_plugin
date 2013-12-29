<?php

function config_filter_day($day_raw)
{   
    //transformando em array
    $day_array = explode(',', $day_raw);
    
    //filtrando valores diferentes de false (0,'',null,array()) 
    $day_valid = array_filter($day_array);
    
    //removendo valores repetidos
    $day_uniq = array_unique($day_valid);
    return $day_uniq;
}
