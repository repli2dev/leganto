<?php

/**
 * Tag selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

namespace Leganto\DB\Tag;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\System,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all tags
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [tag]");
	}

	/**
	 * Find all tags from given book 
	 * @param \Leganto\DB\Book\Entity $book
	 * @param int $language ID of language
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is empty
	 * @throws InvalidArgumentException if language is empty
	 */
	public function findAllByBook(\Leganto\DB\Book\Entity $book, $language) {
		if (empty($book)) {
			throw new InvalidArgumentException("Empty book.");
		}
		if (empty($language)) {
			throw new InvalidArgumentException("Empty language.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_book_tag]")
				->where("[id_book] = %i", $book->bookNode)
				->where("[id_language] = %i", $language)
				->orderBy("name");
	}

	/** @return Entity */
	public function find($id) {
		return Factory::tag()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [tag] WHERE [id_tag] = %i", $id)
		);
	}

	/**
	 * Return all tags beggining with keyword
	 * @param string $keyword
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if keyword is empty
	 */
	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("Empty keyword.");
		}
		$word = mysql_escape_string($keyword) . "%";
		$conditions = "
			[name] LIKE '$word'
		";
		// Do not use view (because view takes over 100 ms)
		return $this->connection->dataSource("SELECT * FROM [tag] WHERE " . $conditions . "");
	}

}