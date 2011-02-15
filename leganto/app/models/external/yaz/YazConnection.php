<?php
class YazConnection
{

	private $driver;

	private $host;

	private $id;

	const QUERY_RPN = 'rpn';

	const RECORD_ARRAY = 'array';

	const RECORD_DATABASE = 'database';

	const RECORD_RAW = 'raw';

	const RECORD_STRING = 'string';

	const RECORD_SYNTAX = 'syntax';

	const RECORD_XML = 'xml';

	public function __construct($host, YazDriver $driver) {
		$this->id = yaz_connect($host);
		$this->checkError();
		$this->host		= $host;
		$this->driver	= $driver;
	}

	public function checkError() {
		$error = yaz_error($this->id);
		if (!empty($error)) {
			throw new YazException($error);
		}
	}

	/** @return YazDriver */
	public function getDriver() {
		return $this->driver;
	}

	public function getHost() {
		return $this->host;
	}

	public function getId() {
		return $this->id;
	}

	public function getNumberOfRecords() {
		$hits = yaz_hits($this->id);
		$this->checkError();
		return $hits;
	}

	public function getResult($format = self::RECORD_ARRAY) {
		return new YazResult($this, $format);
	}

	public function search($query) {
		yaz_search($this->id, self::QUERY_RPN, $query);
		$this->checkError();
	}

	public function setRange($start, $number) {
		yaz_range($this->id, $start, $number);
		$this->checkError();
	}

	public function setSyntax($syntax) {
		yaz_syntax($this->id, $syntax);
		$this->checkError();
	}

	public function wait($timeout, $event) {
		$this->getDriver()->wait($timeout, $event);
	}

}
