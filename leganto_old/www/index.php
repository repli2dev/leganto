<?php
require_once (dirname(__FILE__) . "/constants.php");

// FIXME: probably bug in nette
$params["app_dir"] = $params["appDir"];
// Load bootstrap file
require $params['appDir'] . '/bootstrap.php';
