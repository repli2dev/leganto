<?php
class FollowingImport extends DatabaseTableImport {

	protected function doImport() {
		$following = $this->getSource()->query("SELECT * FROM [reader_recommend]")->fetchAll();
		$language = $this->getDestination()->query("SELECT * FROM [language] WHERE [locale] = 'cs_CZ'")->fetch();
		$this->getDestination()->begin("following");
		$this->getDestination()->query("TRUNCATE TABLE [following]");
		foreach($following AS $row) {
			$this->getDestination()->insert("following", array(
					"id_user"		=> $row["user"],
					"id_user_followed"	=> $row["recommend"],
					))->execute();
		}
		echo $this->getDestination()->dataSource("SELECT * FROM [following]")->count() . " FOLLOWING RELATIONSHIPS IMPORTED\n";
	}

}
