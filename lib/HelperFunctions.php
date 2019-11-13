<?php

/**
 * Returns string between two substrings
 * @param $string
 * @param $start
 * @param $end
 * @return bool|string
 */
function getStringBetween($string, $start = '', $end = '')
{
    if ($start != '') {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
    } else {
        $ini = 0;
    }

    if ($end == '') {
        return substr($string, $ini);
    }
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

/**
 * Converts \n into <br> tag
 * @param $input
 * @return mixed
 */
function convertNewlineIntoLineBreak($input)
{
    return str_replace("\n", '<br>', $input);
}

/**
 * Parsed Meaningfull Text
 * @param $text
 * @return mixed
 */
function convertPropertext($text){
    $sum = getStringBetween($text, 'Summary', 'Experience');
    $ch = explode("\n",$sum);

    $string = '';
    for ($i=0;$i<sizeof($ch);$i++){
//        $ch[$i].="\n";
        if(strlen($ch[$i]) < 68) {
//            $ch[$i].="\n";
            $string .= $ch[$i] . "\n";
        }
        else{
//            $ch[$i] = str_replace("\n", "-", $ch[$i]);
            $string .= $ch[$i];
        }
    }
    return str_replace("\n", "<br>", $string);
}
