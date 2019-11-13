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
    'default_font' => 'serif',    // default font family
    'margin_left' => 0,    	// 15 margin_left
    'margin_right' => 0,    	// 15 margin right
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,     // 9 margin header
    'margin_footer' => 0,     // 9 margin footer
    'orientation' => 'P',  	// L - landscape, P - portrait
);

    //parsing html to pdf
    $mpdf = new Mpdf($mpdfConfig);

    //parsing pdf into text
    $parser = new Parser();

    $pdf = $parser->parseFile('input/' . $the_big_array[10][0] . '.pdf');

    $text = $pdf->getText();


    $pieces = explode("\n", $text);
    $candidateName = trim($pieces[0]);


    $experience = getStringBetween($text, "Experience\n", "Education\n");
//    echo $experience;die;
    $education = convertNewlineIntoLineBreak(getStringBetween($text, 'Education', $candidateName));

    $html = '';

    $html .= '
    <header>
        <div class="cvName container-fluid text-center">
            <div class="mainHeading">
                <h1 id="yay">' . $candidateName . '</h1>
            </div>
        </div>
    </header>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-8 ">
                    <div class="infoLeftSection">
                        <div class="mainDetails">
                            <div class="Objective">
                                <p style="font-weight: bold">OBJECTIVES:</p>
                                <p>' .convertPropertext($text) . '</p>
                            </div>
                            <div class="Experience">
                                <p id="PE" style="font-weight: bold;">EXPERIENCE:</p>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 ">
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
    </body>';

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
//print $html;
//}


