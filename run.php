<?php

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use DiDom\Document;
use Mpdf\MpdfException;

$location = __DIR__ . '/output/';

include 'vendor/autoload.php';
include 'lib/HelperFunctions.php';
include 'lib/htmlFunctions.php';
include 'lib/replaceFunctions.php';

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
for ($i = 0; $i < $big_len; $i++) {
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
for ($file = 10; $file < 11; $file++) {
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

        $getSummary = returnMeaningFullData($summary);
        $getExperience = returnMeaningFullData($experience);
        $getEducation = returnEducation($education);

//CALCULATE LINES
        $firstPageDisplayExp_FLAG = true;
        $newPage_FLAG = true;
        $summary_lines = count(explode('<br>', $getSummary));
        $getSummary = array_slice(explode('<br>', $getSummary), 0, $summary_lines - 1);
        $summary_lines = count($getSummary);
        $getSummary = implode('<br>', $getSummary);
        $experience_lines = count(explode('<br>', $getExperience));
        $total_lines = $summary_lines + $experience_lines + 3;

        $summaryContinue_FLAG = false;
        $experienceContinue_FLAG = false;
        $htmlFirstPage = fPageHtml();

            //NEW PAGE DOESNT EXIST
            if ($total_lines <= 49) {
                $d = [];
                $d2 = [];
                $d[0] = $the_big_array[$file][$skills1Index];
                $d[1] = $the_big_array[$file][$skills2Index];
                $d[2] = $the_big_array[$file][$skills3Index];
                $d2[0] = $the_big_array[$file][$languages1Index];
                $d2[1] = $the_big_array[$file][$languages2Index];
                $d2[2] = $the_big_array[$file][$languages3Index];
                $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                $htmlFirstPage = objectiveTagReplace($getSummary, $htmlFirstPage);
                $htmlFirstPage = experienceTagReplace($getExperience, $htmlFirstPage);
                $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                $htmlFirstPage = emailTagReplace($the_big_array[$file][$emailIndex], $htmlFirstPage);
                $htmlFirstPage = phoneTagReplace($the_big_array[$file][$phoneNumberIndex], $htmlFirstPage);
                $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                $summaryContinue_FLAG = false;
                $experienceContinue_FLAG = false;
                $total_lines = 0;
                $summary_lines =0;
                $experience_lines=0;
            }
            else { //total line > 49
                if ($summary_lines < 48) {
                    if (48 - ($summary_lines) < 3) {
                        // SUMMARY WHOLE
                        // EXPERIENCE PARTIAL
                        $expGetLines = $getExperience;
                        $summary_lines=0;
                        $total_lines = $experience_lines;
                        $d = [];
                        $d2 = [];
                        $d[0] = $the_big_array[$file][$skills1Index];
                        $d[1] = $the_big_array[$file][$skills2Index];
                        $d[2] = $the_big_array[$file][$skills3Index];
                        $d2[0] = $the_big_array[$file][$languages1Index];
                        $d2[1] = $the_big_array[$file][$languages2Index];
                        $d2[2] = $the_big_array[$file][$languages3Index];
                        $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                        $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                        $htmlFirstPage = objectiveTagReplace($getSummary, $htmlFirstPage);
                        $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                        $htmlFirstPage = emailTagReplace($the_big_array[$file][$emailIndex], $htmlFirstPage);
                        $htmlFirstPage = phoneTagReplace($the_big_array[$file][$phoneNumberIndex], $htmlFirstPage);
                        $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                        $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                        $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                        $summaryContinue_FLAG = false;
                        $experienceContinue_FLAG = false;
                    }
                    else {
                        // SUMMARY WHOLE
                        // EXP PARTIAL $expGetLines
                        $expGetLines = implode('<br>', array_slice(explode('<br>', $getExperience), 0, 48 - ($summary_lines + 4)));
                        $remainExpLines = implode('<br>', array_slice(explode('<br>', $getExperience), 48 - ($summary_lines + 4)));
                        $d = [];
                        $d2 = [];
                        $d[0] = $the_big_array[$file][$skills1Index];
                        $d[1] = $the_big_array[$file][$skills2Index];
                        $d[2] = $the_big_array[$file][$skills3Index];
                        $d2[0] = $the_big_array[$file][$languages1Index];
                        $d2[1] = $the_big_array[$file][$languages2Index];
                        $d2[2] = $the_big_array[$file][$languages3Index];
                        $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                        $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                        $htmlFirstPage = objectiveTagReplace($getSummary, $htmlFirstPage);
                        $htmlFirstPage = experienceTagReplace($expGetLines, $htmlFirstPage);
                        $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                        $htmlFirstPage = emailTagReplace($the_big_array[$file][$emailIndex], $htmlFirstPage);
                        $htmlFirstPage = phoneTagReplace($the_big_array[$file][$phoneNumberIndex], $htmlFirstPage);
                        $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                        $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                        $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                        $total_lines = $total_lines - $summary_lines - count(explode('<br>',$expGetLines));
                        $summaryContinue_FLAG = false;
                        $experienceContinue_FLAG = true;
                        $summary_lines=0;
                        $experience_lines=$total_lines;
                    }
                }
                else {
                    $sumLines = implode('<br>', array_slice(explode('<br>', $getSummary), 0, 48));
                    $remainSumLines = implode('<br>', array_slice(explode('<br>', $getSummary), 48));
                    $d = [];
                    $d2 = [];
                    $d[0] = $the_big_array[$file][$skills1Index];
                    $d[1] = $the_big_array[$file][$skills2Index];
                    $d[2] = $the_big_array[$file][$skills3Index];
                    $d2[0] = $the_big_array[$file][$languages1Index];
                    $d2[1] = $the_big_array[$file][$languages2Index];
                    $d2[2] = $the_big_array[$file][$languages3Index];
                    $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                    $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                    $htmlFirstPage = objectiveTagReplace($sumLines, $htmlFirstPage);
                    $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                    $htmlFirstPage = emailTagReplace($the_big_array[$file][$emailIndex], $htmlFirstPage);
                    $htmlFirstPage = phoneTagReplace($the_big_array[$file][$phoneNumberIndex], $htmlFirstPage);
                    $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                    $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                    $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                    $total_lines = $total_lines - 48;
                    $summaryContinue_FLAG = true;
                    $summary_lines=$summary_lines-48;
                }
            }

        $htmlLaterPages =[];
            $pages=0;
            while ($total_lines != 0) {
                $htmlLaterPages[$pages] = returnString();
                if ($total_lines <= 55) {
                    if($summaryContinue_FLAG){
                        $htmlLaterPages[$pages] = objectiveTagReplace2($remainSumLines, $htmlLaterPages[$pages]);
                        $htmlLaterPages[$pages] = experienceTagReplace($getExperience, $htmlLaterPages[$pages]);
                    }
                    elseif ($experienceContinue_FLAG){
                        $htmlLaterPages[$pages] = experienceTagReplace2($remainExpLines, $htmlLaterPages[$pages]);
                    }
                    else {
                        $htmlLaterPages[$pages] = experienceTagReplace2($getExperience, $htmlLaterPages[$pages]);
                    }
                    $total_lines = 0;
                    $summary_lines=0;
                    $experience_lines=0;
                } else { //total line > 49
                    if ($summary_lines < 55) {
                        if (55 - ($summary_lines) < 3) {
                            // SUMMARY WHOLE
                            // EXPERIENCE PARTIAL
                            $expGetLines = $getExperience;
                            $total_lines = $experience_lines;
                            $htmlLaterPages[$pages] = objectiveTagReplace2($remainSumLines, $htmlLaterPages[$pages]);
                            $summary_lines=0;
                        } else {
                            // SUMMARY WHOLE
                            // EXP PARTIAL $expGetLines
                            $expGetLines = implode('<br>', array_slice(explode('<br>', $getExperience), 0, 55 - ($summary_lines + 4)));
                            $remainExpLines = implode('<br>', array_slice(explode('<br>', $getExperience), 55 - ($summary_lines + 4)));
                            $htmlLaterPages[$pages] = objectiveTagReplace2($remainSumLines, $htmlLaterPages[$pages]);
                            $htmlLaterPages[$pages] = experienceTagReplace2($expGetLines, $htmlLaterPages[$pages]);
                            $total_lines = $total_lines - $summary_lines - count(explode('<br>', $expGetLines));
                            $summary_lines=0;
                            $experience_lines=$total_lines;
                        }
                    } else {
                        $sumLines = implode('<br>', array_slice(explode('<br>', $getSummary), 0, 55));
                        $remainSumLines = implode('<br>', array_slice(explode('<br>', $getSummary), 55));
                        $htmlLaterPages[$pages] = objectiveTagReplace2($sumLines, $htmlLaterPages[$pages]);
                        $total_lines = $total_lines - 55;
                        $summary_lines=$summary_lines - 55;
                    }
                }
                $pages++;
            }

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

        $getSummary = '';
        $getExperience = '';

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
            $mpdf->WriteHTML($htmlFirstPage, HTMLParserMode::HTML_BODY);
            for ($i =0;$i < count($htmlLaterPages);$i++){
                $mpdf->AddPage();
                $mpdf->WriteHTML($htmlLaterPages[$i], HTMLParserMode::HTML_BODY);
            }

        } catch (MpdfException $e) {
        }
        try {
            $resumeName = explode(' ', $sections[0]['hugeheading']);
            shell_exec('rm -rf tmp' . $file);
            $mpdf->Output('output/' . $resumeName[0] . '_' . $resumeName[1] . '-Accountant_Manager-New_Jersey .pdf', \Mpdf\Output\Destination::FILE);
            shell_exec('chmod 777 -R output');
        } catch (MpdfException $e) {
        }
    } catch (Throwable $e) {
        echo $file . "->  " . $e->getMessage() . $e->getLine() . "\n";
    }
    $summary = null;
    $experience = null;
}