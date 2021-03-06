<?php
class OpinionsImport extends DatabaseTableImport {

	protected function doImport() {
		$books = $this->getDestination()->query("SELECT * FROM [book]")->fetchPairs("id_book", "id_book");
		$users = $this->getDestination()->query("SELECT * FROM [user]")->fetchPairs("id_user", "id_user");
		$opinions = $this->getSource()->query("SELECT * FROM [reader_opinion] WHERE [book] IN %l", $books, " AND [user] IN %l", $users)->fetchAll();
		$language = $this->getDestination()->query("SELECT * FROM [language] WHERE [locale] = 'cs_CZ'")->fetch();

		$this->getDestination()->query("TRUNCATE TABLE [opinion]");
		$this->getDestination()->query("TRUNCATE TABLE [in_shelf]");

		foreach($opinions AS $opinion) {
			$this->getDestination()->insert("opinion", array(
					"id_language"	=> $language["id_language"],
					"id_user"	=> $opinion["user"],
					"id_book_title"	=> $opinion["book"],
					"rating"	=> $opinion["rating"],
					"inserted"	=> $opinion["date"],
					"content"	=> trim($opinion["content"])
					))->execute();
		}
		echo $this->getDestination()->dataSource("SELECT * FROM [opinion]")->count() . " OPINIONS IMPORTED\n";
	}

}
