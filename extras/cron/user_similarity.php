<?php
/**
 * Cron script for computing users similarity
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

//Debug::timer();

dibi::connect(Environment::getConfig("database"));

$similarity = new DatabaseSimilarity("opinion", "user", "book_title", "rating", 5);
$similarity->checkout();

echo "\nELAPSED TIME: ".Debug::timer()."\n";

echo "\nDONE\n";
?>
