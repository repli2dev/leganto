<?php
require_once dirname(__FILE__) . "/header.php";

Environment::loadConfig(APP_DIR . '/config.ini');

Debug::enable(Debug::DEVELOPMENT);

$destination = dibi::connect(Environment::getConfig("database"));

dibi::begin();
dibi::query("DROP TABLE IF EXISTS [tmp_feed]");
dibi::query("CREATE TABLE [tmp_feed] (INDEX ([id_user])) AS SELECT * FROM [view_feed]");
dibi::commit();
