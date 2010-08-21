<?php
class UsersImport extends DatabaseTableImport {

	protected function doImport() {
		$users = $this->getSource()->query("SELECT * FROM [reader_user]")->fetchAll();
		$language = $this->getDestination()->query("SELECT * FROM [language] WHERE [locale] = 'cs_CZ'")->fetch();
		$this->getDestination()->begin("users");
		$this->getDestination()->query("TRUNCATE TABLE [in_shelf]");
		$this->getDestination()->query("TRUNCATE TABLE [shelf]");
		$this->getDestination()->query("TRUNCATE TABLE [user]");
		foreach($users AS $user) {
			$this->getDestination()->insert("user", array(
					"id_user"		=> $user["id"],
					"id_language"	=> $language["id_language"],
					"role"			=> "common",
					"email"			=> String::lower($user["email"]),
					"password"		=> $user["password"],
					"about"			=> $user["description"],
					"id_user"		=> $user["id"],
					"nick"			=> $user["name"],
					"last_logged"	=> $user["login"],
					"inserted"		=> new DateTime()
					))->execute();

			$this->getDestination()->insert("shelf", array(
					"id_shelf"	=> $user["id"] + 3000,		// HACK
					"id_user"	=> $user["id"],
					"type"		=> "wanted",
					"name"		=> "Chci si přečíst",
					"inserted"		=> new DateTime()
					))->execute();
		}
		echo $this->getDestination()->dataSource("SELECT * FROM [user]")->count() . " USERS IMPORTED\n";
	}

}
