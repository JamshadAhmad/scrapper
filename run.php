<?php
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Smalot\PdfParser\Parser;

require_once __DIR__ . '/vendor/autoload.php';

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

//$stylesheet = file_get_contents('lib/main.css');

for($i = 1; $i<sizeof($the_big_array);$i++) {

    //parsing html to pdf
    $mpdf = new Mpdf();

    //parsing pdf into text
    $parser = new Parser();

    $pdf = $parser->parseFile('input/' . $the_big_array[$i][0] . '.pdf');

    $text = $pdf->getText();

    $pieces = explode("\n", $text);
    $candidateName = trim($pieces[0]);

    $summary = convertNewlineIntoLineBreak(getStringBetween($text, 'Summary', 'Experience'));

    $experience = convertNewlineIntoLineBreak(getStringBetween($text, 'Experience', 'Education'));

    $education = convertNewlineIntoLineBreak(getStringBetween($text, 'Education', $candidateName));;

    $html = '';

    $html .= '<div class="container">';
    $html .= '    <div class="mainHeading">';
    $html .= '           <h1>' . $candidateName . '</h1>';
    $html .= '    </div>';
    $html .= '</div>';
    $html .= '<br/> <br/> <br/> <br/>';
    $html .= '<h3>SUMMARY</h3>';
    $html .= '<p>' . $summary . '</p>';
    $html .= '<br/> <br/> <br/> <br/>';
    $html .= '<h3>EXPERIENCE</h3>';
    $html .= '<p>' . $experience . '</p>';
    $html .= '<br/> <br/> <br/> <br/>';
    $html .= '<h3>EDUCATION</h3>';
    $html .= '<p>' . $education . '</p>';

    //$mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
    $mpdf->Output("output" . $the_big_array[$i][0] . ".pdf", "F");
}


