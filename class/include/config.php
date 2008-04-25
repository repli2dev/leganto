<?php
session_start();

function __autoload($class) {
 require_once "./class/".$class.".php";
}

$temp = new template;
$reader = new reader;
$reader->MySQLconnect();
unset($reader);
$lng = new language;
?>
