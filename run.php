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

/**
 * INITIALIZATION
 */
$csvFileNameWithPath = "lib/csvSC.csv";
$csvStringLengthMax = 10000;
$pdfNameHeading = "PDF Name";
$linkNameHeading = "Links";
$emailNameHeading = "Email";
$phoneNumberHeading = "Phone Number";
$skill1Heading = "Skills 1";
$skill2Heading = "Skills 2";
$skill3Heading = "Skills 3";
$language1Heading = "Languages 1";
$language2Heading = "Languages 2";
$language3Heading = "Languages 3";
$firstPageLinesLimit = 48;
$otherPageLinesLimit = 53;


$csvDataArray = []; // CSV DATA

// Open the file for reading
if (($h = fopen($csvFileNameWithPath, "r")) !== FALSE) {
    while (($data = fgetcsv($h, $csvStringLengthMax, ",")) !== FALSE) {
        $csvDataArray[] = $data;
    }
    fclose($h);
}
$summary = [];
$experience = [];
$education = [];
$big_len = count($csvDataArray[0]);
for ($i = 0; $i < $big_len; $i++) {
    switch ($csvDataArray[0][$i]) {
        case $pdfNameHeading:
            $pdfNameIndex = $i;
            break;

        case $linkNameHeading:
            $linksIndex = $i;
            break;

        case $emailNameHeading:
            $emailIndex = $i;
            break;

        case $phoneNumberHeading:
            $phoneNumberIndex = $i;
            break;

        case $skill1Heading:
            $skills1Index = $i;
            break;

        case $skill2Heading:
            $skills2Index = $i;
            break;

        case $skill3Heading:
            $skills3Index = $i;
            break;

        case $language1Heading:
            $languages1Index = $i;
            break;

        case $language2Heading:
            $languages2Index = $i;
            break;

        case $language3Heading:
            $languages3Index = $i;
            break;

        default:
            break;
    }
}

$file_count = count($csvDataArray);
for ($file = 1; $file < 11; $file++) {
    try {
        shell_exec('lib/pdftohtml input/' . $csvDataArray[$file][$pdfNameIndex] . '.pdf tmp' . $file);
        shell_exec('chmod 777 -R tmp' . $file);

        $allHtml = '';
        $i = 1;
        do {
            $page = 'tmp' . $file . '/page' . $i . '.html';
            $allHtml .= file_get_contents($page);
            $i++;
        } while (file_exists('tmp' . $file . '/page' . $i . '.html'));

        //CREATING DOCUMENT ELEMENT
        $document = new Document($allHtml);
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
        $summaryContinue_FLAG = false;
        $experienceContinue_FLAG = false;
        $summary_lines = count(explode('<br>', $getSummary));
        $getSummary = array_slice(explode('<br>', $getSummary), 0, $summary_lines - 1);
        $summary_lines = count($getSummary);
        $getSummary = implode('<br>', $getSummary);
        $experience_lines = count(explode('<br>', $getExperience));
        $total_lines = $summary_lines + $experience_lines + 3;
        $htmlFirstPage = fPageHtml();

        //NEW PAGE DOESNT EXIST
        if ($total_lines <= $firstPageLinesLimit + 1) {
            $d = [];
            $d2 = [];
            $d[0] = $csvDataArray[$file][$skills1Index];
            $d[1] = $csvDataArray[$file][$skills2Index];
            $d[2] = $csvDataArray[$file][$skills3Index];
            $d2[0] = $csvDataArray[$file][$languages1Index];
            $d2[1] = $csvDataArray[$file][$languages2Index];
            $d2[2] = $csvDataArray[$file][$languages3Index];
            $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
            $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
            $htmlFirstPage = objectiveTagReplace($getSummary, $htmlFirstPage);
            $htmlFirstPage = experienceTagReplace($getExperience, $htmlFirstPage);
            $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
            $htmlFirstPage = emailTagReplace($csvDataArray[$file][$emailIndex], $htmlFirstPage);
            $htmlFirstPage = phoneTagReplace($csvDataArray[$file][$phoneNumberIndex], $htmlFirstPage);
            $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
            $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
            $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
            $summaryContinue_FLAG = false;
            $experienceContinue_FLAG = false;
            $total_lines = 0;
            $summary_lines = 0;
            $experience_lines = 0;
        } else { //total line > 49
            if ($summary_lines < $firstPageLinesLimit) {
                if ($firstPageLinesLimit - ($summary_lines) < 3) {
                    // SUMMARY WHOLE
                    // EXPERIENCE PARTIAL
                    $summary_lines = 0;
                    $total_lines = $experience_lines;
                    $d = [];
                    $d2 = [];
                    $d[0] = $csvDataArray[$file][$skills1Index];
                    $d[1] = $csvDataArray[$file][$skills2Index];
                    $d[2] = $csvDataArray[$file][$skills3Index];
                    $d2[0] = $csvDataArray[$file][$languages1Index];
                    $d2[1] = $csvDataArray[$file][$languages2Index];
                    $d2[2] = $csvDataArray[$file][$languages3Index];
                    $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                    $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                    $htmlFirstPage = objectiveTagReplace($getSummary, $htmlFirstPage);
                    $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                    $htmlFirstPage = emailTagReplace($csvDataArray[$file][$emailIndex], $htmlFirstPage);
                    $htmlFirstPage = phoneTagReplace($csvDataArray[$file][$phoneNumberIndex], $htmlFirstPage);
                    $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                    $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                    $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                    $summaryContinue_FLAG = false;
                    $experienceContinue_FLAG = false;
                } else {

                    // SUMMARY WHOLE
                    // EXP PARTIAL $expGetLines
                    $expGetLines = implode('<br>', array_slice(explode('<br>', $getExperience), 0, $firstPageLinesLimit - ($summary_lines + 4)));
                    $remainExpLines = implode('<br>', array_slice(explode('<br>', $getExperience), $firstPageLinesLimit - ($summary_lines + 4)));
                    $d = [];
                    $d2 = [];
                    $d[0] = $csvDataArray[$file][$skills1Index];
                    $d[1] = $csvDataArray[$file][$skills2Index];
                    $d[2] = $csvDataArray[$file][$skills3Index];
                    $d2[0] = $csvDataArray[$file][$languages1Index];
                    $d2[1] = $csvDataArray[$file][$languages2Index];
                    $d2[2] = $csvDataArray[$file][$languages3Index];
                    $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                    $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                    $htmlFirstPage = objectiveTagReplace($getSummary, $htmlFirstPage);
                    $htmlFirstPage = experienceTagReplace($expGetLines, $htmlFirstPage);
                    $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                    $htmlFirstPage = emailTagReplace($csvDataArray[$file][$emailIndex], $htmlFirstPage);
                    $htmlFirstPage = phoneTagReplace($csvDataArray[$file][$phoneNumberIndex], $htmlFirstPage);
                    $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                    $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                    $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                    $total_lines = $total_lines - $summary_lines - count(explode('<br>', $expGetLines));
                    $summaryContinue_FLAG = false;
                    $experienceContinue_FLAG = true;
                    $summary_lines = 0;
                    $experience_lines = $total_lines;
                }
            } else {
                $sumLines = implode('<br>', array_slice(explode('<br>', $getSummary), 0, $firstPageLinesLimit));
                $remainSumLines = implode('<br>', array_slice(explode('<br>', $getSummary), $firstPageLinesLimit));
                $d = [];
                $d2 = [];
                $d[0] = $csvDataArray[$file][$skills1Index];
                $d[1] = $csvDataArray[$file][$skills2Index];
                $d[2] = $csvDataArray[$file][$skills3Index];
                $d2[0] = $csvDataArray[$file][$languages1Index];
                $d2[1] = $csvDataArray[$file][$languages2Index];
                $d2[2] = $csvDataArray[$file][$languages3Index];
                $htmlFirstPage = nameTagReplace($sections[0]['hugeheading'], $htmlFirstPage);
                $htmlFirstPage = subNameTagReplace($sections[2]['subheading'], $htmlFirstPage);
                $htmlFirstPage = objectiveTagReplace($sumLines, $htmlFirstPage);
                $htmlFirstPage = educationTagReplace($getEducation, $htmlFirstPage);
                $htmlFirstPage = emailTagReplace($csvDataArray[$file][$emailIndex], $htmlFirstPage);
                $htmlFirstPage = phoneTagReplace($csvDataArray[$file][$phoneNumberIndex], $htmlFirstPage);
                $htmlFirstPage = addressTagReplace($sections[1]['subheading'], $htmlFirstPage);
                $htmlFirstPage = skillTagReplace($d, $htmlFirstPage);
                $htmlFirstPage = languageTagReplace($d2, $htmlFirstPage);
                $total_lines = $total_lines - $firstPageLinesLimit;
                $summaryContinue_FLAG = true;
                $summary_lines = $summary_lines - $firstPageLinesLimit;
            }
        }

        $lastLine_FLAG = true;
        $htmlLaterPages = [];
        $pages = 0;
        while ($total_lines != 0) {
            $htmlLaterPages[$pages] = returnString();
            if ($total_lines <= $otherPageLinesLimit) {
                if ($summaryContinue_FLAG) {
                    $htmlLaterPages[$pages] = objectiveTagReplace2($remainSumLines, $htmlLaterPages[$pages]);
                    $htmlLaterPages[$pages] = experienceTagReplace($getExperience, $htmlLaterPages[$pages]);
                } elseif ($experienceContinue_FLAG) {
                    if ($lastLine_FLAG) {
                        $htmlLaterPages[$pages] = experienceTagReplace2($remainExpLines, $htmlLaterPages[$pages]);
                    } else {
                        $htmlLaterPages[$pages] = experienceTagReplace2($remainExpLines, $htmlLaterPages[$pages]);
                    }
                } else {
                    $htmlLaterPages[$pages] = experienceTagReplace2($getExperience, $htmlLaterPages[$pages]);
                }
                $total_lines = 0;
                $summary_lines = 0;
                $experience_lines = 0;
                $experienceContinue_FLAG = false;
                $summaryContinue_FLAG = false;
            } else {
                //total line > 49
                if ($summary_lines <= $otherPageLinesLimit && $summary_lines != 0) {
                    if ($otherPageLinesLimit - ($summary_lines) < 3) {
                        // SUMMARY WHOLE
                        // EXPERIENCE PARTIAL
                        $expGetLines = $getExperience;
                        $total_lines = $experience_lines;
                        $htmlLaterPages[$pages] = objectiveTagReplace2($remainSumLines, $htmlLaterPages[$pages]);
                        $summary_lines = 0;
                    } else {
//                            echo count(explode('<br>',$remainSumLines));
                        // SUMMARY WHOLE
                        // EXP PARTIAL $expGetLines
//                            echo $summary_lines;
                        $expGetLines = implode('<br>', array_slice(explode('<br>', $getExperience), 0, $otherPageLinesLimit - ($summary_lines + 4)));
                        $remainExpLines = implode('<br>', array_slice(explode('<br>', $getExperience), $otherPageLinesLimit - ($summary_lines + 4)));
                        $htmlLaterPages[$pages] = objectiveTagReplace2($remainSumLines, $htmlLaterPages[$pages]);
                        $htmlLaterPages[$pages] = experienceTagReplace2($expGetLines, $htmlLaterPages[$pages]);
                        $total_lines = $total_lines - $summary_lines - count(explode('<br>', $expGetLines));
                        $experienceContinue_FLAG = true;
                        $summary_lines = 0;
                    }
                } elseif ($summary_lines > 53) {
                    $sumLines = implode('<br>', array_slice(explode('<br>', $getSummary), 0, $otherPageLinesLimit));
                    $remainSumLines = implode('<br>', array_slice(explode('<br>', $getSummary), $otherPageLinesLimit));
                    $htmlLaterPages[$pages] = objectiveTagReplace2($sumLines, $htmlLaterPages[$pages]);
                    $total_lines = $total_lines - 53;
                    $summary_lines = $summary_lines - 53;
                } elseif ($experienceContinue_FLAG) {
                    $expGetLines = implode('<br>', array_slice(explode('<br>', $remainExpLines), 0, $otherPageLinesLimit));
                    $remainExpLines = implode('<br>', array_slice(explode('<br>', $remainExpLines), $otherPageLinesLimit));
                    $htmlLaterPages[$pages] = experienceTagReplace2($expGetLines, $htmlLaterPages[$pages]);
                    $experienceContinue_FLAG = true;
                    $total_lines = $total_lines - 53;
                    $lastLine_FLAG = true;

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
            for ($i = 0; $i < count($htmlLaterPages); $i++) {
                $mpdf->AddPage();
                $mpdf->WriteHTML($htmlLaterPages[$i], HTMLParserMode::HTML_BODY);
            }

        } catch (MpdfException $e) {
        }
        try {
            $resumeName = explode(' ', $sections[0]['hugeheading']);
            shell_exec('rm -rf tmp' . $file);
            $mpdf->Output('output/' . $resumeName[0] . '_' . $resumeName[1] . '-Accountant_Manager-New_Jersey_ ' . $file . ' .pdf', \Mpdf\Output\Destination::FILE);
            shell_exec('chmod 777 -R output');
        } catch (MpdfException $e) {
        }
    } catch (Throwable $e) {
        echo $file . "->  " . $e->getMessage() . $e->getLine() . "\n";
    }
    $summary = null;
    $experience = null;
}