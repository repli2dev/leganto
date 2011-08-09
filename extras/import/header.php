<?php
define("IMPORT_DIR", dirname(__FILE__));

require_once IMPORT_DIR . '/../document_root/constants.php';

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->addDirectory(IMPORT_DIR);
$loader->register();

