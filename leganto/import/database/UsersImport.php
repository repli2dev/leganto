<?php
class UsersImport extends DatabaseTableImport {

    protected function doImport() {
	$users = $this->getSource()->query("SELECT * FROM [reader_user]")->fetchAll();
	$language = $this->getDestination()->query("SELECT * FROM [language] WHERE [name] = 'czech'")->fetch();
	$this->getDestination()->begin("users");
	foreach($users AS $user) {
	    $this->getDestination()->insert("user", array(
		"id_user"		=> $user["id"],
		"id_language"	=> $language["id_language"],
		"id_role"		=> "common",
		"email"		=> String::lower($user["email"]),
		"password"		=> $user["password"],
		"id_user"		=> $user["id"],
		"nick"		=> utf8_decode($user["name"]),	// FIXME
		"inserted"		=> new DibiVariable("now()", "sql")
		))->execute();

	    $this->getDestination()->insert("shelf", array(
		"id_shelf"		=> $user["id"],			// HACK
		"id_user"		=> $user["id"],
		"type"		=> "read",
		"name"		=> "Mám přečteno",
		"inserted"		=> new DibiVariable("now()", "sql")
		))->execute();

	    $this->getDestination()->insert("shelf", array(
		"id_shelf"		=> $user["id"] + 2000,		// HACK
		"id_user"		=> $user["id"],
		"type"		=> "wanted",
		"name"		=> "Chci si přečíst",
		"inserted"		=> new DibiVariable("now()", "sql")
		))->execute();
	}
	$this->getDestination()->end("users");

    }

}
