<?php
require_once(dirname(__FILE__) . "/constants.php");

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

echo "\n Dropping table with similarity... ";
dibi::query("DROP TABLE IF EXISTS [book_similarity]");
echo dibi::$elapsedTime;

echo "\n Create table with similarity... ";
dibi::query("CREATE TABLE [book_similarity] (INDEX([id_book_from]), INDEX([id_book_to])) SELECT
                `from`.`id_book` AS `id_book_from`,
                `to`.`id_book`  AS `id_book_to`,
                2 * COUNT([from].[id_tagged]) / (SELECT COUNT([id_tagged]) FROM [tagged] WHERE [id_book] = [from].[id_book] OR [id_book] = [to].[id_book]) AS [value]
        FROM `tagged` AS `from`
        INNER JOIN `tagged` AS `to` ON `to`.`id_tag` = `from`.`id_tag`
        WHERE `from`.`id_book` != `to`.`id_book`
        GROUP BY `from`.`id_book`, `to`.`id_book`
");
echo dibi::$elapsedTime;
 
die("\nDONE\n");
?>