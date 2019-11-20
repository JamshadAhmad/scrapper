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
        if (strlen($text[$i]) <= 76) {
            $string .= $text[$i] . "<br>";
        } else {
            $string .= ' ' . $text[$i];
        }
    }
    return $string;
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
   return str_replace('•',"<br> •", $text);
}

/**
 * @param $test
 * @return string
 */

function adjustLines($test)
{

    try {
        $str = '';
        $t_s = $test;
        $test_len = strlen($test);
        $end = 75;
        if ($test_len < 75) {
            $str .= $test . "<br>";
        } else {
            while ($t_s != '') {
                $test_len = strlen($t_s);
                if ($test_len < 75) {
                    $end = $test_len;
                } elseif ($test_len >= 75) {
                    $end = $end - strpos(strrev(substr($t_s, 0, $end)), ' ');
                }
                $str .= substr($t_s, 0, $end) . "<br>";
                $t_s = trim(substr($t_s, $end));
                $end = 75;
            }
        }
        return $str;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}