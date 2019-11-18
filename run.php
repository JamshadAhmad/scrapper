<?php
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use DiDom\Document;
use Mpdf\MpdfException;

$location = __DIR__ .'/output/';

include 'vendor/autoload.php';
include 'lib/HelperFunctions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$the_big_array = [];

// Open the file for reading
if (($h = fopen("lib/Cosmo Shahzeen.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
        $the_big_array[] = $data;
    }
    fclose($h);
}

shell_exec('lib/pdftohtml input/10.pdf tmp');
shell_exec('chmod 777 -R tmp');
$finalHtml='';
$i = 1;
do{
    $page = 'tmp/page'.$i.'.html';
    $finalHtml .= file_get_contents($page);
    $i ++;
} while (file_exists('tmp/page'.$i.'.html'));

$document = new Document($finalHtml);
$spans = $document->find('span');

$i = -1;

foreach($spans as $span) {
    $heading = whichHeading(getFontSize($span -> style));
    if($heading == 'hugeheading' || $heading == 'bigheading' || $heading == 'subheading') {
        $i ++;
        $sections[$i][$heading] = $span -> text();
    }
    else {
        $sections[$i][$heading][] = $span -> text();
    }
}

$summary = [];
$experience = [];
$education = [];
$title = '';

$sec_len = count($sections);


for($x = 3;$x < 6;$x++)
{
    if($sections[$x]['bigheading'] == "Summary")
    {
        $summary = $sections[$x]['text'];
    }
    else if($sections[$x]['bigheading'] == "Experience")
    {
        $experience = $sections[$x]['text'];
    }
    else if($sections[$x]['bigheading'] == "Education")
    {
        $education = $sections[$x]['text'];
    }
}

//CALCULATE LINES

//summary
//print_r($summary);die;
$ex = convertPropertext($summary);
$final_summary = bulletCheck($ex);
$final_summary = str_replace("\n \n", "\n", $final_summary);

//echo $final_summary;die;
$ex = explode("\n",$final_summary);
$l = count($ex);
//print_r($ex);die;
$s = '';
//for($i = 0 ;$i<$l;$i++){
//    $s .= adjustLines($ex[$i]);
//}
//echo adjustLines($ex[15]);die;
//echo $s;die;
$test = "my name is khan shahzeen and i live in lahore gulberg 3 pakistan asia annanas a andy order imma like to eat chocolate plus i'm an independentant fanners aas andy gorder imma like to eat chocolate plus i'm a barcleona fanas";
echo adjustLines($test);die;
//$len = count($test);
//echo strrev(strrev($test));die;
//echo strlen($test);die;
//for ($i = 0; $i < $len; ++$i) {

//echo adjustLines($test);die;
//}


$final_summary = bulletCheck($ex);
$final_summary = str_replace("<br> <br>", "<br>", $final_summary);

//experience
$ex2 = convertPropertext($experience);


//print_r($final_summary);die;

$html = '';
$html .= '
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width"/>
        <link type="text/css" rel="stylesheet" href="lib/main.css">
        <link type="text/css" rel="stylesheet" href="lib/bootstrap.min.css">
    </head>        
    <body>
        <div class="cvName container-fluid text-center">
            <div class="mainHeading">
                <h1 id="yay">' . $sections[0]['hugeheading'] . ' </h1>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">   
                <div class="col-xs-8" style="width: 69.99%;!important;">
                    <div class="infoLeftSection">
                        <div class="mainDetails" >
                            <div class="Objective" >
                                <p style="font-weight: bold;font-size: 18px;letter-spacing: -0.5px;">RESUME OBJECTIVES:</p>
                                <p class ="final" style="font-size: 14px;">' . $final_summary . '</p>
                            </div>
                            <div class="Experience">
                                <p id="PE" style="font-weight: bold;font-style: italic;font-size: 16px;">EXPERIENCE:</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 " >
                    <div class="infoRightSection">
                        <div class="extraDetails">
                            <div class="email">
                                <p style="font-weight: bold">Email:</p>
                                <p>' . $the_big_array[10][2] . '</p>
                            </div>
                            <div class="phone">
                                <p style="font-weight: bold">Phone:</p>
                                <p>' . $the_big_array[10][3] . '</p>
                            </div>
                            <div class="linkL">
                                <p style="font-weight: bold">Link:</p>
                                ' . $the_big_array[10][1] . '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>';

//echo $html;
//die;

$stylesheet = file_get_contents('lib/bootstrap.min.css');
$stylesheet2 = file_get_contents('lib/main.css');
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_footer' => 0,
    'orientation' => 'P',
);


//parsing html to pdf
try {
    $mpdf = new Mpdf($mpdfConfig);

} catch (MpdfException $e) {
}
try {
    $mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
} catch (MpdfException $e) {
}
try {
    $mpdf->WriteHTML($stylesheet2, HTMLParserMode::HEADER_CSS);
} catch (MpdfException $e) {
}
try {
    $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
} catch (MpdfException $e) {
}
try {
    $mpdf->Output();
} catch (MpdfException $e) {
}
