<?php

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/../app');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/../libs');

// absolute filesystem path to the models
define('MODELS_DIR', APP_DIR . '/models');

// absolute filesystem path to te locales
define('LOCALES_DIR', APP_DIR . '/locales');

// load bootstrap file
require APP_DIR . '/bootstrap.php';
