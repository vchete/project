<?php

function getNombreGenero($alias)
{
    $alias = strtolower($alias);
    $genero = ['m' => 'Masculino', 'f' => 'Femenino'];   
    
    if (isset($genero[$alias])) {
        return $genero[$alias];
    }

    return null;
}

function getFormatDateCarbon($date) 
{
    return str_replace('/', '-', $date);
}

function getSlugString ($string)
{
    
    $replace   = ['Á','É','Í','Ó','Ú','á','é','í','ó','ú','ä','ë','ï','ö','ü','ñ',' ','-'];
    $replaceTo = ['a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','n','_','_'];
    $string    = strtolower($string);
    $newString = str_replace($replace, $replaceTo, $string);
    return $newString;
}