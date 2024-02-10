<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function price_format($astr, $des=2)
{
    $bstr = 0;
    if($astr != ''){
        $astr = str_replace(',', '', $astr);
        if(is_numeric($astr)){
            $bstr = number_format($astr, $des);
        }
        
        if(MY_LANGUAGE_ABBR == 'bn'){
            $en_no = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            $bn_no = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
            $astr = number_format($astr, $des);
            $bstr = str_replace($en_no, $bn_no, $astr);
        }
    }
    return $bstr;
}
