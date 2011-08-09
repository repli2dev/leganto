<?php
// Defines basic path variable to run Nette

// absolute filesystem path to this web root
$params['wwwDir'] = __DIR__;

// absolute filesystem path to the application root
$params['appDir'] = realpath(__DIR__ . '/../app');
