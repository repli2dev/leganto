<?php
require_once dirname(__FILE__) . "/header.php";

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

// Remove old new pass codes
Leganto::users()->getUpdater()->removeOldPassCodes();