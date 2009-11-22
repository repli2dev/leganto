<?php
require_once(dirname(__FILE__) . "/constants.php");
require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

Debug::timer();

echo "EXTRACTING ALL TEXT FOR TRANSLATION...";

$ge = new GettextExtractor();

// Set Filters
$ge->setFilter('php', 'PHP');
$ge->setFilter('phtml','NetteLatte');

echo "<pre>";
$ge->scan(APP_DIR . '/WebModule');

$ge->save(APP_DIR . '/locale/messages.pot');
echo "</pre>";

echo "\nELAPSED TIME: ".Debug::timer()."\n";

die("\nDONE\n");
?>
