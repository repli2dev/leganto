<?php

require_once dirname(__FILE__) . '/../Filters/PHPFilter.php';

$pf = new PHPFilter();

$data = $pf->extract(dirname(__FILE__) . '/testfile.php');

var_dump($data);