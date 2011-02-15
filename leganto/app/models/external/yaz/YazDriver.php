<?php
class YazDriver
{

	/** @return YazConnection */
	public function connect($host) {
		return new YazConnection($host, $this);
	}

	public function pool(array $hosts) {
		$connections = array();
		foreach($hosts AS $host) {
			$connections[] = $this->connect($host);
		}
		return new YazConnectionPool($connections, $this);
	}

	public function wait($timeout = NULL, $event = NULL) {
		$options = array();
		if ($timeout !== NULL) {
			$options["timeout"] = $timeout;
		}
		if ($event !== NULL) {
			$options["event"] = $event;
		}
		if (!yaz_wait($options)) {
			throw new YazException("Unexpected error has happened.");
		}
		
	}

}
