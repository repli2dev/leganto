<?php
/**
 * Cron script for removing odl pass codes (generaly for user maintainance)
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
require_once dirname(__FILE__) . "/header.php";

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

// Remove old new pass codes
Leganto::users()->getUpdater()->removeOldPassCodes();