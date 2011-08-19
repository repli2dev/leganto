<?php

use Nette\Diagnostics\Debugger,
    Nette\Application\Routers\Route,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\SimpleRouter,
    Leganto\DB\Factory,
    Leganto\Logger,
    Leganto\Localization\Translator,
    Leganto\Localization\Environment,
    Leganto\Templating\Template,
    Leganto\Templating\Helpers,
    MailPanel\MailPanel;


// Step 1: Load Nette Framework
require __DIR__ . '/../libs/Nette/loader.php';

// Step 2: Load configuration
$configurator = new Nette\Configurator;
$configurator->container->params += $params;
$configurator->container->params['tempDir'] = __DIR__ . '/../temp';
$container = $configurator->loadConfig(__DIR__ . '/config.neon');

// Step 3: Start session
$container->getService("session")->setExpiration("+7 days");
$container->getService("session")->start();

// Step 4: Set debugging
$debug = $container->params["debug"];
if ($debug["enable"]) {
	// Set debugger and enable it
	Debugger::$strictMode = TRUE;
	Debugger::$logDirectory = __DIR__ . '/../log';
	Debugger::enable();
	
	// Enable session dummy mailer & panel extension
	MailPanel::register($container);

	// Not catching exception to show error
	$container->application->catchExceptions = false;
	// Libs can contain bunch of function showing warnings
	error_reporting(E_ALL ^ E_USER_WARNING);
} else {
	// Complete suppresion of errors on production mode
	error_reporting(0);
	// Show 404 and 500 error instead of exceptions
	$container->application->catchExceptions = true;
	$container->application->errorPresenter = 'Front:Error';
}

// Step 5: Setup routing
$router = $container->application->getRouter();
ApiModule\Routes::add($router);
CronModule\Routes::add($router);
FrontModule\Routes::add($router);

// Step 6: Database connection
// Lazy connect should be enabled in config.neon
$connection = new DibiConnection($container->params["database"]);
$container->addService("database", $connection);
Factory::setConnection($connection);

// Step 8: Register logger
$container->addService("logger", new Logger($container));

// Step 9: Register language environment (according to domain)
$container->addService("environment", new Environment($container));

// Step 10: Register translator
$container->addService("translator", new Translator($container));

// Step 11: Some classes need Container
Template::setContainer($container);
Helpers::setContainer($container);

// FIXME: user expiration
//Environment::getUser()->setExpiration("+ 7 days", FALSE);
// Run the application!
$container->application->run();
