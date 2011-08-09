<?php
require_once(dirname(__FILE__) . "/header.php");

define('SIM_LIMIT', isset($GLOBALS['argv'][1]) ? $GLOBALS['argv'][1] : 1);
define('LEN_LIMIT', isset($GLOBALS['argv'][2]) ? $GLOBALS['argv'][2] : 4);
define('TAGGED_LIMIT', isset($GLOBALS['argv'][3]) ? $GLOBALS['argv'][3] : 2);
Debug::enable(Debug::DEVELOPMENT);

// Limit for similarity
Debug::enable(Debug::DEVELOPMENT);

// Database
Environment::loadConfig(APP_DIR . '/config.ini');
$config = Environment::getConfig("database");
dibi::connect($config);

// Tags
$tags = Leganto::tags()->fetchAndCreateAll(Leganto::tags()->getSelector()->findAll());
// Number of usage
$numbers = dibi::query("SELECT [tag].[id_tag] AS [id], COUNT([tag].[id_tag]) AS [number] FROM [tag] LEFT JOIN [tagged] USING ([id_tag]) GROUP BY [id]")->fetchPairs("id", "number");

echo "   --- NUMBER OF TAGS: " . count($tags) . "\n";

foreach($tags AS $first) {
	foreach($tags AS $second) {
		if ($first->getId() >= $second->getId()) continue;
		if (strlen($first->name) <= LEN_LIMIT && strlen($second->name) <= LEN_LIMIT)  continue;
		if ($numbers[$first->getId()] >= TAGGED_LIMIT &&  $numbers[$second->getId()] >= TAGGED_LIMIT) continue;
		if (ExtraString::similarity($first->name, $second->name) <= SIM_LIMIT) {
			if ($numbers[$first->getId()] > $numbers[$second->getId()]) {
				$superior = $first;
				$inferior = $second;
			}
			else {
				$superior = $second;
				$inferior = $first;
			}
			echo "   [" . $superior->getId() . "] '" . $superior->name . "' :: [" . $inferior->getId() . "] '" . $inferior->name . "'\n";
			if ($first->name == $second->name) {
					Leganto::tags()->getUpdater()->merge($superior, $inferior);
					echo "   ---- MERGED\n";
			}
			else {
				do {
					$query = readline("   Do you want to merge these tags? (y/n)  ");
				} while($query !== "y" && $query !== "n");
				if ($query === "y") {
					Leganto::tags()->getUpdater()->merge($superior, $inferior);
					echo "   ---- MERGED\n";
				}
			}			
		}
	}
}
