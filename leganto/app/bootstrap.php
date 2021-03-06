<?php
// Step 1: Load Nette Framework
// this allows Nette to load classes automatically so that
// you don't have to litter your code with 'require' statements
require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

if (@$_SERVER['HTTP_HOST'] == "devel.ctenari.cz") {
	Environment::setName("devel-ctenari");
}

//Environment::setName("Kronos");

Environment::getApplication()->catchExceptions = false;
Environment::loadConfig(APP_DIR . '/config.ini');

// Step 2: Enable Nette\Debug
$debug = Environment::getConfig('debug');

if ($debug->enable) {
	Debug::enable(null, $debug->log, $debug->email);
	if ($debug->profiler) {
		Debug::enableProfiler();
	}
	// Libs contain bunch of function returning warnings
	error_reporting(E_ALL^E_USER_WARNING);
} else {
	error_reporting(0);
}

// Step 3: Get the front controller
$application = Environment::getApplication();

// Step 4: Setup application router
$router = $application->getRouter();

$router[] = ApiModule::createRouter();
$router[] = WebModule::createRouter();
$router[] = CronModule::createRouter();

// Step 5: Database connection
// lazy connect should be enabled in config.ini
dibi::connect(Environment::getConfig('database'));

// Profiling SQL queries
//dibi::getProfiler()->setFile(APP_DIR.'/temp/log.sql');

// Step 6: Start session
Environment::getSession()->setExpiration("+7 days");
Environment::getSession()->start();

Environment::getUser()->setExpiration("+ 7 days", FALSE);

// Step 7: Run the application!
$application->run();

