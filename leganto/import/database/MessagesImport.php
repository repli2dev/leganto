<?php
class MessagesImport extends DatabaseTableImport {

	protected function doImport() {
		$messages = $this->getSource()->query("SELECT * FROM [reader_message]")->fetchAll();

		//$this->getDestination()->begin("message");
		//$this->getDestination()->query("TRUNCATE TABLE [message]");
		foreach($messages as $message) {
			$this->getDestination()->insert("message", array(
				"id_user_from"	=> $message["usFrom"],
				"id_user_to"	=> $message["usTo"],
				"content"	=> $message["content"],
				"read"		=> $message["isRead"]-1,
				"inserted"	=> $message["date"],
				"deleted_by_recipient" => $message["toDestroy"],
				"deleted_by_owner" => $message["fromDestroy"]
			))->execute();
		}
		echo $this->getDestination()->dataSource("SELECT * FROM [message]")->count() . " MESSAGES TO READ IMPORTED\n";
	}

}
