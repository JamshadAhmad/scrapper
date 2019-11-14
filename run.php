<?php
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use DiDom\Document;

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
$html = '';
$html .= '
        
<body>
    <div class="cvName container-fluid text-center">
        <div class="mainHeading">
            <h1 id="yay">' . $sections[0]['hugeheading'] . '</h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-8 ">
                <div class="infoLeftSection">
                    <div class="mainDetails">
                        <div class="Objective">
                            <p style="font-weight: bold">OBJECTIVES:</p>
                            <p></p>
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

$stylesheet = file_get_contents('lib/bootstrap.min.css');
$stylesheet2 = file_get_contents('lib/main.css');
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font_size' => 0,
    'default_font' => 'serif',
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => 0,
    'orientation' => 'P',
);


//parsing html to pdf
try {
    $mpdf = new Mpdf($mpdfConfig);
} catch (\Mpdf\MpdfException $e) {
}
try {
    $mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
} catch (\Mpdf\MpdfException $e) {
}
try {
    $mpdf->WriteHTML($stylesheet2, HTMLParserMode::HEADER_CSS);
} catch (\Mpdf\MpdfException $e) {
}
try {
    $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
} catch (\Mpdf\MpdfException $e) {
}
try {
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
}
