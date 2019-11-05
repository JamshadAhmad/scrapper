<?php

include 'vendor/autoload.php';
include 'lib/HelperFunctions.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('input/10.pdf');

$text = $pdf->getText();

$pieces = explode("\n", $text);
$candidateName = trim($pieces[0]);

echo '<br>==Candidate==<br>';
echo $candidateName;

echo '<br>==Summary==<br>';
echo convertNewlineIntoLineBreak(getStringBetween($text, 'Summary', 'Experience'));

echo '<br>==Experience==<br>';
echo convertNewlineIntoLineBreak(getStringBetween($text, 'Experience', 'Education'));

echo '<br>==Education==<br>';
echo convertNewlineIntoLineBreak(getStringBetween($text, 'Education', $candidateName));
