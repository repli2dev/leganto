<?php

/**
 * YAZ Result
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\External\Yaz;

use Leganto\External\Yaz\YazConnection;

class YazResult implements IteratorAggregate, Countable {

	private $connection;
	private $format;

	const FORMAT_ARRAY = 'array';

	const FORMAT_DATABASE = 'database';

	const FORMAT_RECORD = 'record';

	const FORMAT_RAW = 'raw';

	const FORMAT_STRING = 'string';

	const FORMAT_SYNTAX = 'syntax';

	const FORMAT_XML = 'xml';

	public function __construct(YazConnection $connection, $format = self::FORMAT_ARRAY) {
		$this->connection = $connection;
		$this->format = $format;
	}

	public function count() {
		return $this->connection->getNumberOfRecords();
	}

	public function getConnection() {
		return $this->connection;
	}

	public function getIterator() {
		return new YazResultIterator($this);
	}

	public function entry($position, $format = NULL) {
		if ($format === NULL)
			$format = $this->format;
		if ($format !== self::FORMAT_RECORD) {
			$record = yaz_record($this->connection->getId(), $position, $format);
			$this->connection->checkError();
			return $record;
		} else {
			$record = yaz_record($this->connection->getId(), $position, self::FORMAT_XML);
			$this->connection->checkError();
			return YazRecord::fromXml($record);
		}
	}

}

class YazResultIterator implements Iterator {

	private $position;

	/** @var YazResult */
	private $result;
	private $size;

	public function __construct(YazResult $result) {
		$this->result = $result;
		$this->position = 1;
		$this->size = $result->count();
	}

	public function current() {
		if ($this->position <= $this->size) {
			return $this->result->entry($this->position);
		} else {
			return NULL;
		}
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		$this->position++;
	}

	public function rewind() {
		$this->position = 1;
	}

	public function valid() {
		return $this->position <= $this->size;
	}

}