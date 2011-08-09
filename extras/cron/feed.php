<?php
/**
 * Cron script for speeding work with feed
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

//Debug::enable(Debug::DEVELOPMENT);

$destination = dibi::connect(Environment::getConfig("database"));

dibi::begin();
dibi::query("DROP TABLE IF EXISTS [tmp_feed]");
dibi::query("CREATE TABLE [tmp_feed] (INDEX ([id_user])) AS SELECT * FROM [view_feed]");
dibi::commit();
