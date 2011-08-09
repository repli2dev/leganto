<?php
class ShelvesImport extends DatabaseTableImport {

	protected function doImport() {
		$toRead = $this->getSource()->query("SELECT * FROM [reader_readlist]")->fetchAssoc("user,id");

		$this->getDestination()->begin("readlist");
		$this->getDestination()->query("TRUNCATE TABLE [in_shelf]");
		foreach($toRead AS $user) {
			$i = 0;
			foreach ($user AS $item) {
				$this->getDestination()->insert("in_shelf", array(
						"id_shelf"		=> $item["user"],
						"id_book"		=> $item["book"],
						"order"			=> $i++,
						"inserted"		=> new DateTime()
						))->execute();
			}
		}
		echo $this->getDestination()->dataSource("SELECT * FROM [in_shelf]")->count() . " BOOKS TO READ IMPORTED\n";
	}

}
