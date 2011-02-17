<?php
class YazConnectionPool
{

	private $connections;

	private $driver;

	public function  __construct(array $connections, YazDriver $driver) {
		$this->connections = $connections;
		$this->driver = $driver;
	}

	public function search($query, $timeout = NULL, $format = YazResult::FORMAT_RECORD) {
		foreach($this->connections AS $connection) {
			$connection->search($query);
		}
		$this->getDriver()->wait($timeout);
		$result = array();
		foreach($this->connections AS $connection) {
			$result[] = $connection->getResult($format);
		}
		return $result;
	}

	public function setRange($start, $number) {
		foreach($this->connections AS $connection) {
			$connection->setRange($start, $number);
		}
	}

	public function setSyntax($syntax) {
		foreach($this->connections AS $connection) {
			$connection->setSyntax($syntax);
		}
	}

	/** @return YazDriver */
	public function getDriver() {
		return $this->driver;
	}
}