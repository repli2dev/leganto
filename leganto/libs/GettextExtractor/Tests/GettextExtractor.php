<?php

require_once dirname(__FILE__) . '/../GettextExtractor.class.php';

//$outputFile = $argv[1];
$outputFile = 'output.po';
//$inputFiles = array_slice($argv, 2);
$inputFiles = array('testfile.php', 'testfile.phtml');

$ge = new GettextExtractor();

$ge->filters['php'] = array('PHP');
$ge->filters['phtml'] = array('PHP', 'NetteCurlyBrackets');

$data = $ge->extract($inputFiles);

$ge->write($outputFile);

echo '<pre>';
echo 'GettextExtractor test';
var_dump($data);
echo '</pre>';