<?php

/**
 * YAZ Connection
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\External\Yaz;

use InvalidArgumentException,
    Leganto\External\Yaz\YazDriver,
    Leganto\External\Yaz\YazException,
    Leganto\External\Yaz\YazResult;

class YazConnection {

	private $driver;
	private $host;
	private $id;
	private $options;

	const OPT_CHARSET = 'charset';

	const QUERY_RPN = 'rpn';

	public function __construct($host, YazDriver $driver, $options = array()) {
		if (empty($options)) {
			$this->id = yaz_connect($host);
		} else {
			$this->id = yaz_connect($host, $options);
		}
		$this->checkError();
		$this->host = $host;
		$this->driver = $driver;
		$this->options = $options;
	}

	public function checkError() {
		$error = yaz_error($this->id);
		if (!empty($error)) {
			throw new YazException("[$this->host] " . $error);
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

	public function getOptions() {
		return $this->options;
	}

	public function getResult($format = YazResult::FORMAT_ARRAY) {
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
