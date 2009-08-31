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
dibi::query("DROP TABLE IF EXISTS [user_similarity]");
echo dibi::$elapsedTime;

echo "\n Creating table user_similarity... ";
dibi::query("CREATE TABLE [user_similarity] SELECT
                `from`.`id_user` AS `id_user_from`,
                SUM(
                        CASE ABS(`from`.`rating` - `to`.`rating`)
                                WHEN 0 THEN 16
                                WHEN 1 THEN 9
                                WHEN 2 THEN 4
                                WHEN 3 THEN 2
                                WHEN 4 THEN 0
                        END
                )
                /
                ((SELECT COUNT(*) FROM `opinion` WHERE `opinion`.`id_user` = `from`.`id_user`)*16) AS `value`,
                `to`.`id_user`  AS `id_user_to`
        FROM `opinion` AS `from`
        INNER JOIN opinion AS `to` ON `to`.id_book = `from`.id_book
        WHERE `from`.`id_user` != `to`.`id_user`
        GROUP BY `from`.id_user, `to`.id_user;
");
echo dibi::$elapsedTime;
 
die("\nDONE\n");
?>