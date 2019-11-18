<?php

/**
 * Parsed Meaningfull Text
 * @param $text
 * @return mixed
 */
function convertPropertext($text)
{
    $string = '';
    $len = count($text);
    for ($i = 0; $i < $len; ++$i) {
        if (strlen($text[$i]) <= 75) {
            $string .= $text[$i] . "\n";
        } else {
            $string .= ' ' . $text[$i];
        }
    }
    return $string;
//    return str_replace("\n", "<br>", $string);
}

/**
 * @param string $styleString
 * @return string
 */
function getFontSize($styleString)
{
    $styles = explode(';', $styleString);
    foreach ($styles as $style) {
        $substyle = explode(':', $style);
        if ($substyle[0] === 'font-size') {
            return $substyle[1];
        }
    }
}

/***
 * @param $fontsize
 * @return string
 */
function whichHeading($fontsize) {
    if ((int)$fontsize >= 20 ) return 'hugeheading';
    elseif((int)$fontsize >= 15 ) return 'bigheading';
    elseif((int)$fontsize >= 13 ) return 'subheading';
    else return 'text';
}

/**
 * @param $text
 * @return string
 */
function bulletCheck ($text)
{
   return str_replace('•',"\n •", $text);
}

/**
 * @param $test
 * @return string
 */

function adjustLines($test){

    $str = '';
    $test_len = strlen($test);
    $req_len = ceil($test_len / 74) + 1;
    $start =0;
    $total_chars = 75;
    $end = 75;

    if ($test_len < 75) {
        $str .= $test . "\n";
    }
    else if ($test_len >= 75) {
        while()
        {
            if ($test[$end] != ' ') {
                $str_update = $end - strpos(strrev(substr($test, $start, $total_chars)), ' ');
                $str_update = $str_update - $start;
                $str .= substr($test, $start, $str_update) . "\n";
                echo "START: " . $start . "\n END: " . $end . "\n UPDATE: " . $str_update . "\n";
                $start = $start + $str_update;
                $end = $start + $total_chars;
//                echo $start . " " . $total_chars;
                if ($end >= $test_len) {
                    echo $end;
                    $end = $test_len;
                    echo "last\n";
                    $a = $req_len -1;
                }
//                echo $a . "{}";
            } else {
                $str .= substr($test, $start, $total_chars) . "\n";
                $start = $start + $total_chars;
                echo $a . "--";
            }
            echo $str . "\n";
        }
    }
die;
    return $str;
}