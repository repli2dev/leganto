<?php
use	Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Configurator;
// Step 1: Load Nette Framework
require __DIR__ . '/../libs/Nette/loader.php';


// Step 2: Load configuration
$configurator = new Configurator;
$configurator->container->params += $params;
$configurator->container->params['tempDir'] = __DIR__ . '/../temp';
$container = $configurator->loadConfig(__DIR__ . '/config.neon');

// Step 3: Set debugging

$debug = Environment::getConfig('debug');
if ($debug->enable) {
	Debugger::$strictMode = TRUE;
	Debugger::$logDirectory = __DIR__ . '/../log';
	Debugger::enable();
	if ($debug->profiler) {
		Debug::enableProfiler();
	}
	// Not catching exception to show error
	Environment::getApplication()->catchExceptions = false;
	// Libs can contain bunch of function showing warnings
	error_reporting(E_ALL^E_USER_WARNING);
} else {
	// Complete suppresion of errors on production mode
	error_reporting(0);
	// Show 404 and 500 error instead of exceptions
	Environment::getApplication()->catchExceptions = true;
}

// Step 4: Enable RobotLoader - this allows load all classes automatically
// so that you don't have to litter your code with 'require' statements
$container->robotLoader->register();

// Step 5: Setup application router
$router = $container->getRouter();

$router[] = ApiModule::createRouter();
$router[] = WebModule::createRouter();
$router[] = CronModule::createRouter();

// Step 6: Database connection
// lazy connect should be enabled in config.ini
dibi::connect(Environment::getConfig('database'));

// Step 7: Start session
$container->getService("session")->setExpiration("+7 days");
$container->getService("session")->start();

Environment::getUser()->setExpiration("+ 7 days", FALSE);

// Step 8: Run the application!
$container->application->run();

