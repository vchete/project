<?php
namespace Hcode\Project\Helpers;

use App\Http\Controllers\Controller;


class hcFunctions extends Controller {
    public static function getNombreGenero($alias)
    {
        $alias = strtolower($alias);
        $genero = ['m' => 'Masculino', 'f' => 'Femenino'];   
        
        if (isset($genero[$alias])) {
            return $genero[$alias];
        }
    
        return null;
    }
    
    public static function  getFormatDateCarbon($date) 
    {
        return str_replace('/', '-', $date);
    }
    
    public static function  getSlugString ($string)
    {
        
        $replace   = ['Á','É','Í','Ó','Ú','á','é','í','ó','ú','ä','ë','ï','ö','ü','ñ',' ','-'];
        $replaceTo = ['a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','n','_','_'];
        $string    = strtolower($string);
        $newString = str_replace($replace, $replaceTo, $string);
        return $newString;
    }

}
