<?php
require_once dirname(__FILE__) . "/header.php";
Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

dibi::query("DELETE FROM [book] WHERE [id_book] NOT IN (SELECT DISTINCT([id_book]) FROM [book_title])");

dibi::query("DELETE FROM [in_shelf] WHERE [id_book] NOT IN (SELECT [id_book] FROM [book])");

dibi::query("ALTER TABLE [in_shelf] DROP FOREIGN KEY [in_shelf_ibfk_1]");

dibi::query("ALTER TABLE [in_shelf] DROP INDEX [id_book]");

$books = dibi::query("SELECT * FROM [book_title]")->fetchPairs("id_book", "id_book_title");

$inShelf = dibi::query("SELECT * FROM [in_shelf]");

while ($record = $inShelf->fetch()) {
	dibi::query("UPDATE [in_shelf] SET [id_book] = %i", $books[$record->id_book], " WHERE [id_in_shelf] = %i", $record->id_in_shelf);
}

dibi::query("ALTER TABLE [in_shelf] change [id_book] [id_book_title] INT(25) UNSIGNED NOT NULL COMMENT 'kniha v polici'");

dibi::query("ALTER TABLE [in_shelf] ADD UNIQUE([id_book_title], [id_shelf])");

dibi::query("ALTER TABLE [in_shelf] ADD FOREIGN KEY([id_book_title]) REFERENCES [book_title]([id_book_title])");

dibi::loadFile(__DIR__ . "/../../resources/database/views.sql");