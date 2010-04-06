<?php
require_once dirname(__FILE__) . "/header.php";

Environment::loadConfig(APP_DIR . '/config.ini');

Debug::enable(Debug::DEVELOPMENT);

Debug::timer();

dibi::connect(Environment::getConfig("database"));

$similarity = new DatabaseSimilarity("tagged", "book", "tag");
$similarity->checkout();

echo "\nELAPSED TIME: ".Debug::timer()."\n";

die("\nDONE\n");
?>
