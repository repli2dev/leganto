<?php

// Step 1: Load Nette Framework
// this allows Nette to load classes automatically so that
// you don't have to litter your code with 'require' statements
require_once LIBS_DIR . '/Nette/loader.php';

// Step 2: Enable Nette\Debug
// for better exception and error visualisation
Debug::enable();

// Step 3: Get the front controller
$application = Environment::getApplication();

// Step 4: Run the application!
$application->run();
