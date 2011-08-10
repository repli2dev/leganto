<?php

/**
 * YAZ Edition finder
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\External;

use InvalidArgumentException,
    Leganto\DB\Factory,
    Leganto\External\Yaz\YazDriver;

class YazEditionFinder implements IFinder {

	private $limit;
	private $pool;

	public function __construct($limit = 10) {
		$this->limit = $limit;
	}

	public function get($book) {
		if (!($book instanceof \Leganto\DB\Book\Entity)) {
			throw new InvalidArgumentException("Wrong book given.");
		}
		// Title
		$title = $book->title . ($book->subtitle == NULL ? '' : ' ' . $book->subtitle);
		// Authors
		// Only one author is used
		$authorEntity = Factory::author()->fetchAndCreate(
			Factory::author()->getSelector()->findAllByBook($book)->applyLimit(1)
		);
		if ($authorEntity->type === \Leganto\DB\Author\Entity::PERSON) {
			$author = $authorEntity->lastname;
		} else {
			$author = $authorEntity->groupname;
		}
		// Language
		$language = Factory::language()->getSelector()->find($book->languageId)->z3950;
		// Searching
		$records = $this->getPool()->search('@and @attr 1=4 "' . $title . '" @attr 1=1003 @attr 4=1 "' . $author . '"');
		$editions = array();
		foreach ($records AS $result) {
			$limit = 1;
			foreach ($result AS $record) {
				$data = $record->getData();
				if (isset($data->isbn) && isset($data->language) && $data->language == $language) {
					$editions[$data->isbn] = $record;
				}
				$limit++;
				if ($limit > $this->limit)
					break;
			}
		}
		return $editions;
		;
	}

	/** @return YazConnectionPool */
	private function getPool() {
		if (!isset($this->pool)) {
			$driver = new YazDriver();
			$connections = array(
			    $driver->connect('aleph.nkp.cz:9991/SKC-UTF', array(YazDriver::OPT_CHARSET => 'UTF-8')),
				//$driver->connect('aleph.mzk.cz:9991/MZK01-UTF', array('charset' => 'UTF-8')),
			);
			$this->pool = $driver->pool($connections);
		}
		return $this->pool;
	}

}
