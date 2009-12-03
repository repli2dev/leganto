<?php
require_once dirname(__FILE__) . "/header.php";

// DESTINATION
Environment::loadConfig(APP_DIR . '/config.ini');

Debug::enable(Debug::DEVELOPMENT);

$config = (array) Environment::getConfig("database");
$config["host"] = "mysql5-innodb";
$destination = dibi::connect($config, "destination");

// SOURCE
$config = new Config();
$config->add("host", "mysql5");
$config->add("database", "reader");
$config->add("username", "reader");
$config->add("password", "****");
$config->add("charset", "utf8");
$source = dibi::connect($config, "source");

// IMPORTS
$opinions   = new OpinionsImport($source, $destination);
$books	    = new BooksImport($source, $destination);
//$books->import();
$opinions->import();
