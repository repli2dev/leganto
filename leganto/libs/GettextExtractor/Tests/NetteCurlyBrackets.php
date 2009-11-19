<?php

require_once dirname(__FILE__) . '/../Filters/NetteCurlyBracketsFilter.php';

$pf = new NetteCurlyBracketsFilter();

$data = $pf->extract(dirname(__FILE__) . '/testfile.phtml');

var_dump($data);