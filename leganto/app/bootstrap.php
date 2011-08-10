<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\SimpleRouter;


// Step 1: Load Nette Framework
require __DIR__ . '/../libs/Nette/loader.php';

// Step 2: Load configuration
$configurator = new Nette\Configurator;
$configurator->container->params += $params;
$configurator->container->params['tempDir'] = __DIR__ . '/../temp';
$container = $configurator->loadConfig(__DIR__ . '/config.neon');

// Step 3: Set debugging
$debug = $container->params["debug"];
if ($debug["enable"]) {
	// Set debugger and enable it
	Debugger::$strictMode = TRUE;
	Debugger::$logDirectory = __DIR__ . '/../log';
	Debugger::enable();
	
	// Not catching exception to show error
	$container->application->catchExceptions = false;
	// Libs can contain bunch of function showing warnings
	error_reporting(E_ALL^E_USER_WARNING);
} else {
	// Complete suppresion of errors on production mode
	error_reporting(0);
	// Show 404 and 500 error instead of exceptions
	$container->application->catchExceptions = true;
	$container->errorPresenter = 'Error';
}

// Step 4: Setup routing
$router = $container->application->getRouter();
ApiModule\Routes::add($router);
CronModule\Routes::add($router);
FrontModule\Routes::add($router);

// Step 6: Database connection
// Lazy connect should be enabled in config.neon
$connection = new DibiConnection($container->params["database"]);
$container->addService("database", $connection);
Leganto\DB\Factory::setConnection($connection);

// Step 7: Start session
$container->getService("session")->setExpiration("+7 days");
$container->getService("session")->start();

// FIXME: user expiration
//Environment::getUser()->setExpiration("+ 7 days", FALSE);

// Run the application!
$container->application->run();
