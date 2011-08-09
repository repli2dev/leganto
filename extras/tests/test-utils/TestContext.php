<?php
class TestContext
{

	private $connection;

	public function  __construct(DibiConnection $connection) {
		$this->connection = $connection;
	}

	public function getConnection() {
		return $this->connection;
	}

	public function resetDatabase() {
		$this->connection->query("SET foreign_key_checks=off");
		$this->connection->loadFile(APP_DIR . '/../../resources/database/tables.sql');
		$this->connection->loadFile(APP_DIR . '/../../resources/database/views.sql');
		$this->connection->loadFile(__DIR__ . '/data.sql');
		$this->connection->query("SET foreign_key_checks=on");
	}

}
