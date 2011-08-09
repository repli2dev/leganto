<?php
class YazDriver
{
	const OPT_CHARSET = YazConnection::OPT_CHARSET;

	/** @return YazConnection */
	public function connect($host, $options = array()) {
		return new YazConnection($host, $this, $options);
	}

	public function pool(array $connections) {
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
