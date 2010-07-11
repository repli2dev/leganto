<?php
require_once dirname(__FILE__) . "/header.php";

// DESTINATION
Environment::loadConfig(APP_DIR . '/config.ini');

Debug::enable(Debug::DEVELOPMENT);

$config = Environment::getConfig("database");
$destination = dibi::connect($config, "destination");

// SOURCE
$config = new Config();
$config->add("host", "localhost");
$config->add("database", "reader");
$config->add("username", "root");
$config->add("password", "");
$config->add("charset", "utf8");
$source = dibi::connect($config, "source");

// IMPORTS
$schema     = new DatabaseSchemaImport($destination);
$schema->setSource(new File(dirname(__FILE__) . "/../../resources/database/tables.sql"));
$schema->setSource(new File(dirname(__FILE__) . "/../../resources/database/views.sql"));

$system     = new SystemImport($destination);
$users      = new UsersImport($source, $destination);
$books	    = new BooksImport($source, $destination);
$opinions   = new OpinionsImport($source, $destination);
$tags       = new TagsImport($source, $destination);
$discussions= new DiscussionImport($source, $destination);

Debug::timer();
//$schema->import();
//echo "IMPORTED: DATABASE SCHEMA\n";
//$system->import();
//echo "IMPORTED: SYSTEM INFO\n";
//$users->import();
//$books->import();
$opinions->import();
$tags->import();
$discussions->import();

echo "-------------------------------------------";
echo "\nELAPSED TIME: ".Debug::timer()."\n";
echo "-------------------------------------------";
