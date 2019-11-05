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

?>