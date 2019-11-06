<?php
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Smalot\PdfParser\Parser;

require_once __DIR__ . '/vendor/autoload.php';
$location = __DIR__ .'/output/';

include 'vendor/autoload.php';
include 'lib/HelperFunctions.php';


$the_big_array = [];

// Open the file for reading

if (($h = fopen("lib/Cosmo Shahzeen.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
        $the_big_array[] = $data;
    }

    fclose($h);
}

$stylesheet = file_get_contents('lib/bootstrap.min.css');
$stylesheet2 = file_get_contents('lib/main.css');


//for($i = 1; $i<sizeof($the_big_array);$i++) {

$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',    // format - A4, for example, default ''
    'default_font_size' => 0,     // font size - default 0
    'default_font' => '',    // default font family
    'margin_left' => 0,    	// 15 margin_left
    'margin_right' => 0,    	// 15 margin right
    'margin_top' => 0,
    'margin_header' => 0,     // 9 margin header
    'margin_footer' => 0,     // 9 margin footer
    'orientation' => 'P'  	// L - landscape, P - portrait
);

    //parsing html to pdf
    $mpdf = new Mpdf($mpdfConfig);

    //parsing pdf into text
    $parser = new Parser();

    $pdf = $parser->parseFile('input/' . $the_big_array[1][0] . '.pdf');

    $text = $pdf->getText();

    $pieces = explode("\n", $text);
    $candidateName = trim($pieces[0]);

    $summary = convertNewlineIntoLineBreak(getStringBetween($text, 'Summary', 'Experience'));

    $experience = convertNewlineIntoLineBreak(getStringBetween($text, 'Experience', 'Education'));

    $education = convertNewlineIntoLineBreak(getStringBetween($text, 'Education', $candidateName));;

    $html = '';

    $html .= '<div class="cvName container-fluid text-center">';
    $html .= '    <div class="mainHeading">';
    $html .= '           <h1>' . $candidateName . '</h1>';
    $html .= '    </div>';
    $html .= '</div>';
    $html .= '<div class="container-fluid">';
    $html .= '    <div class="row">';
    $html .= '        <div class="col-xs-8 ">';
    $html .= '            <div class="infoLeftSection">';
    $html .= '                <div class="mainDetails">';
    $html .= '                    <div class="Objective">Objectives:' . $summary . '</div>';
    $html .= '                    <div class="Experience">Experience:' . $experience . '</div>';
    $html .= '                </div>';
    $html .= '            </div>';
    $html .= '        </div>';
    $html .= '        <div class="col-xs-3 ">';
    $html .= '            <div class="infoRightSection">';
    $html .= '                <div class="extraDetails">';
    $html .= '                    <div class="email">Email:' . $the_big_array[1][2] . ' </div>';
    $html .= '                    <div class="phone">Phone:' . $the_big_array[1][3] . '</div>';
    $html .= '                    <div class="linkL">Link:' . $the_big_array[1][1] . '</div>';
    $html .= '                </div>';
    $html .= '            </div>';
    $html .= '        </div>';
    $html .= '    </div>';
    $html .= '</div>';

//    $html .= '<br/> <br/> <br/> <br/>';
//    $html .= '<h3>SUMMARY</h3>';
//    $html .= '<p>' . $summary . '</p>';
//    $html .= '<br/> <br/> <br/> <br/>';
//    $html .= '<h3>EXPERIENCE</h3>';
//    $html .= '<p>' . $experience . '</p>';
//    $html .= '<br/> <br/> <br/> <br/>';
//    $html .= '<h3>EDUCATION</h3>';
//    $html .= '<p>' . $education . '</p>';
















    $mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($stylesheet2, HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
    $mpdf->Output();
//}


