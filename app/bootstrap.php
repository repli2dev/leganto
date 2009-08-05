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

Environment::loadConfig(APP_DIR . '/config.ini');
// Step 2: Enable Nette\Debug
// for better exception and error visualisation
//Debug::enable();

$debug = Environment::getConfig('debug');

if ($debug->enable) {
	Debug::enable(null, $debug->log, $debug->email);
	if ($debug->profiler) {
		Debug::enableProfiler();
		RoutingDebugger::enable();
	}
}

// Step 3: Get the front controller
$application = Environment::getApplication();

// Step 4: Setup application router
$router = $application->getRouter();

$router[] = new Route(
	"admin/<presenter>/<action>",
	array(
		'module' => 'Backend',
		'presenter' => 'Default',
		'action' => 'default'
	)
);

$router[] = new Route(
	'<module>/<presenter>/<action>',
	array(
		'presenter' => 'Default',
		'action' => 'default'
	)
);

$router[] = new Route(
	'/',
	array(
		'module' => 'Frontend',
		'presenter' => 'Default',
		'action' => 'default'
	)
);

$router[] = new Route(
	"/admin",
	array(
		"module" => "Backend",
		'presenter' => 'Default',
		'action' => 'default'
	)
);


// Step 5: Database connection
// lazy connect should be enabled in config.ini
dibi::connect(Environment::getConfig('database'));

// Step 6: Run the application!
$application->run();
