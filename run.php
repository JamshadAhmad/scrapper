include 'checking.html'
<?php

use Mpdf\Mpdf;
use Smalot\PdfParser\Parser;

require_once __DIR__ . '/vendor/autoload.php';

include 'vendor/autoload.php';
include 'lib/HelperFunctions.php';

//parsing html to pdf
$mpdf = new Mpdf();

//parsing pdf into text
$parser = new Parser();
$pdf = $parser->parseFile('input/1.pdf');

$text = $pdf->getText();

$pieces = explode("\n", $text);
$candidateName = trim($pieces[0]);

$summary = convertNewlineIntoLineBreak(getStringBetween($text, 'Summary', 'Experience'));

$experience = convertNewlineIntoLineBreak(getStringBetween($text, 'Experience', 'Education'));

$education = convertNewlineIntoLineBreak(getStringBetween($text, 'Education', $candidateName));;

$html = '';


$html = '';

$html .= '<div class="jumbotron">';
$html .= '    <div class="mainHeading" style="color:white;
                width: 100%;
                text-align:center;
                background-color:black;">';
$html .= '           <h1>' . $candidateName .'</h1>';
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

$mpdf->WriteHTML($html);
$mpdf->Output();










