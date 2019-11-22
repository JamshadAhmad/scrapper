<?php
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use DiDom\Document;
use Mpdf\MpdfException;

$location = __DIR__ .'/output/';

include 'vendor/autoload.php';
include 'lib/HelperFunctions.php';

ini_set('display_errors', 1);
ini_set('max_execution_time', 600);
set_time_limit(600);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$the_big_array = [];

// Open the file for reading
if (($h = fopen("lib/csvSC.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
        $the_big_array[] = $data;
    }
    fclose($h);
}
$summary = [];
$experience = [];
$education = [];
$big_len = count($the_big_array[0]);
for($i = 0; $i < $big_len; $i++) {
    switch ($the_big_array[0][$i]) {
        case "PDF Name":
            $pdfNameIndex = $i;
            break;

        case "Links":
            $linksIndex = $i;
            break;

        case "Email":
            $emailIndex = $i;
            break;

        case "Phone Number":
            $phoneNumberIndex = $i;
            break;

        case "Skills 1":
            $skills1Index = $i;
            break;

        case "Skills 2":
            $skills2Index = $i;
            break;

        case "Skills 3":
            $skills3Index = $i;
            break;

        case "Languages 1":
            $languages1Index = $i;
            break;

        case "Languages 2":
            $languages2Index = $i;
            break;

        case "Languages 3":
            $languages3Index = $i;
            break;

        default:
            break;
    }
}

$file_count = count($the_big_array);
for($file = 3; $file <5; $file++) {
    try {
        shell_exec('lib/pdftohtml input/' . $the_big_array[$file][$pdfNameIndex] . '.pdf tmp' . $file);
        shell_exec('chmod 777 -R tmp' . $file);

        $finalHtml = '';
        $i = 1;
        do {
            $page = 'tmp' . $file . '/page' . $i . '.html';
            $finalHtml .= file_get_contents($page);
            $i++;
        } while (file_exists('tmp' . $file . '/page' . $i . '.html'));

        $document = new Document($finalHtml);
        $spans = $document->find('span');

        $i = -1;
        $sections = [];
        foreach ($spans as $span) {
            $heading = whichHeading(getFontSize($span->style));
            if ($heading == 'hugeheading' || $heading == 'bigheading' || $heading == 'subheading') {
                $i++;
                $sections[$i][$heading] = $span->text();
            } else {
                $sections[$i][$heading][] = $span->text();
            }
        }

        $title = '';
        $sec_len = count($sections);
        for ($x = 0; $x < $sec_len; $x++) {
            if (array_key_exists('bigheading', $sections[$x]) && $sections[$x]['bigheading'] == 'Summary') {
                $summary = $sections[$x]['text'];
            }
            if (array_key_exists('bigheading', $sections[$x]) && $sections[$x]['bigheading'] == 'Experience') {
                $experience = $sections[$x]['text'];
            }
            if (array_key_exists('bigheading', $sections[$x]) && $sections[$x]['bigheading'] == 'Education') {
                $education = $sections[$x]['text'];
            }
        }


//summary
        if($summary !=  null){
            $ex = convertPropertext($summary);
            $final_summary = bulletCheck($ex);
            $final_summary = str_replace("<br> <br>", "<br>", $final_summary);
            $ex = explode("<br>", $final_summary);
            $l = count($ex);
            $s = '';
            for ($i = 0; $i < $l; $i++) {
                $s .= adjustLines($ex[$i]);
            }
        }
        else {
            $s='';
        }

//experience
        if($experience != null){
            $ex2 = convertPropertext($experience);
            $final_exp = bulletCheck($ex2);
            $final_exp = str_replace("<br> <br>", "<br>", $final_exp);
            $ex2 = explode("<br>", $final_exp);
            $l2 = count($ex2);
            $s2 = '';
            for ($i = 0; $i < $l2; $i++) {
                $s2 .= adjustLines($ex2[$i]);
            }
        }
        else{
            $s2 = '';
        }

//CALCULATE LINES

//        $summary_lines = count(explode('<br>',$s));
//        $experience_lines = count(explode('<br>',$s2));
////        $education_lines =
//
//        if()
//
//        $total_lines = count(explode('<br>',$s));
//        $total_lines = $total_lines + count(explode())




        $html1 = '';
        $html1 .= '
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
            </div>
            <div class="mainSubHeading">
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">   
                <div class="col-xs-8" style="width: 69.99%;!important;">
                    <div class="infoLeftSection">
                        <div class="mainDetails" >
                            <div class="Objective" >
                            </div>
                            <div class="Experience">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 " >
                    <div class="infoRightSection">
                        <div class="extraDetails">
                            <div class="email">
                            </div>
                            <div class="phone">
                            </div>
                            <div class="address">
                            </div>
                            <div class="skills">
                                <div class="skill2">
                                </div>
                                <div class="skill3">
                                </div>
                            </div>
                            <div class="language">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>';

        /**
         * COLUMN-8 Tags
         */
        $name_tag = '
    <div class="mainHeading">
        <h1 id="yay">' . $sections[0]['hugeheading'] . ' </h1>
    </div>';

        $subName_tag = '
    <div class="mainSubHeading">
        <h4 id="yay">' . $sections[2]['subheading'] . ' </h4>
    </div>';

        $objective_tag = '
    <div class="Objective" >
        <p style="font-weight: bold;font-size: 18px;letter-spacing: -0.5px;">RESUME OBJECTIVES:</p>
        <p class ="final" style="font-size: 13.3px; color: black;font-weight: lighter">' . $s . '</p>
    </div>';

        $experience_tag = '
    <div class="Experience">
        <p id="PE" style="font-weight: bold;font-style: italic;font-size: 16px;">EXPERIENCE:</p>
        <p class ="final" style="font-size: 13.3px; color: black;font-weight: lighter">' . $s2 . '</p>
    </div>';


        /**
         * COLUMN-3 Tags
         */
        $email_tag = '
    <div class="email" style="line-height: 85%;">
        <div class="row" style="padding: 0; margin: 0">
            <div class="col-xs-2" style="margin: 0;padding: 0">
                <img src="lib/circle.png" width="21px" height="21px">
            </div>
            <div class="col-xs-9" style="padding: 0; margin: 0; width: 50%!important;">
                <p style="font-size: 11px; font-weight: bold; padding-right: 0;margin-top: 4px;">   
                ' . $the_big_array[$file][$emailIndex] . '
                </p>
            </div>
        </div>
    </div>';

        $phone_tag = '
    <div class="phone" style="line-height: 85%;">
        <div class="row" style="padding: 0; margin: 0">
            <div class="col-xs-2" style="margin: 0;padding: 0">
                <img src="lib/phone.png" width="19px" height="19px">
            </div>
            <div class="col-xs-9" style="padding: 0; margin: 0; width: 50%!important;">
                <p style="font-size: 11px; font-weight: bold; padding-right: 0;margin-top: 4px;">
                ' . $the_big_array[$file][$phoneNumberIndex] . '
                </p>
            </div>
        </div>
    </div>';

        $address_tag = '
    <div class="address" style="line-height: 85%;">
        <div class="row" style="padding: 0; margin: 0">
            <div class="col-xs-2" style="margin: 0;padding: 0">
                <img src="lib/location.png" width="19px" height="19px">
            </div>
            <div class="col-xs-9" style="padding: 0; margin: 0; width: 50%!important;">
                <p style="font-size: 11px; font-weight: bold; padding-right: 0;margin-top: 4px;">
                ' . $sections[1]['subheading'] . '
                </p>
            </div>
        </div>
    </div> <br>';


        /**
         * NAME REPLACEMENT
         */
        try {
            if ($sections[0]['hugeheading'] != '') {
                $html1 = str_replace('<div class="mainHeading">
            </div>', $name_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * SUBNAME REPLACEMENT
         */
        try {
            if ($sections[2]['subheading'] != '') {
                $html1 = str_replace('<div class="mainSubHeading">
            </div>', $subName_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * OBJECTIVE REPLACEMENT
         */
        try {
            if ($s != '') {
                $html1 = str_replace('<div class="Objective" >
                            </div>', $objective_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * EXPERIENCE REPLACEMENT
         */
        try {
            if ($s2 != '') {
                $html1 = str_replace('<div class="Experience">
                            </div>', $experience_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * EMAIL REPLACEMENT
         */
        try {
            if ($the_big_array[$file][$emailIndex] != "N/A" && $the_big_array[$file][$emailIndex] != "NA") {
                $html1 = str_replace('<div class="email">
                            </div>', $email_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * PHONE REPLACEMENT
         */
        try {
            if ($the_big_array[$file][$phoneNumberIndex] != "NA" && $the_big_array[$file][$phoneNumberIndex] != "N/A") {
                $html1 = str_replace('<div class="phone">
                            </div>', $phone_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * ADDRESS REPLACEMENT
         */
        try {
            if ($sections[1]['subheading'] != '') {
                $html1 = str_replace('<div class="address">
                            </div>', $address_tag, $html1);
            }
        } catch (Throwable $e) {
        }

        /**
         * LANGUAGE REPLACEMENT
         */
        try {
            $lan_data='';
            if ($the_big_array[$file][$languages1Index] != "NA" &&
                $the_big_array[$file][$languages1Index] != "N/A" ||
                $the_big_array[$file][$languages2Index] != "NA" &&
                $the_big_array[$file][$languages2Index] != "N/A" ||
                $the_big_array[$file][$languages3Index] != "NA" &&
                $the_big_array[$file][$languages3Index] != "N/A")
            {
                $lan_data .= '
                <div class="languages" style="line-height: 85%;">
                    <p style="font-weight: bold">Languages:</p>';
                if($the_big_array[$file][$languages1Index] != "NA" &&
                    $the_big_array[$file][$languages1Index] != "N/A"){
                    $lan_data .='<p>' . $the_big_array[$file][$languages1Index] . '</p>';
                }
                if($the_big_array[$file][$languages2Index] != "NA" &&
                    $the_big_array[$file][$languages2Index] != "N/A"){
                    $lan_data .='<p>' . $the_big_array[$file][$languages2Index] . '</p>';
                }
                if($the_big_array[$file][$languages3Index] != "NA" &&
                    $the_big_array[$file][$languages3Index] != "N/A"){
                    $lan_data .='<p>' . $the_big_array[$file][$languages3Index] . '</p>';
                }
                $lan_data .='</div>';
                $html1 = str_replace('<div class="language">
                            </div>', $lan_data, $html1);
                echo "REPLACED";
            }

        } catch (Throwable $e) {
        }

//        echo $html1;die;
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
            'orientation' => 'P'
        );



        $s='';
        $s2='';
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
            $mpdf->WriteHTML($html1, HTMLParserMode::HTML_BODY);
        } catch (MpdfException $e) {
        }
        try {
            shell_exec('rm -rf tmp' . $file);
            $mpdf->Output('output/CV' . $the_big_array[$file][$pdfNameIndex] . '.pdf', \Mpdf\Output\Destination::FILE);
            shell_exec('chmod 777 -R output');
        } catch (MpdfException $e) {
        }
    } catch (Throwable $e) {
    }
    $summary= null;
    $experience = null;
}


