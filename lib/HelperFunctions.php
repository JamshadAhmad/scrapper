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
        if (strlen($text[$i]) <= 70) {
            $string .= $text[$i] . '<br>';
        } else {
            $string .= ' ' . $text[$i];
        }
    }
    return str_replace("\n", "<br>", $string);
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
