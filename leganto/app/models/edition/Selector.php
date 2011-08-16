<?php

/**
 * Edition selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Edition;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    InvalidArgumentException,
    Leganto\ORM\IEntity,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {

	/**
	 * Find one edition
	 * @param int $id id of edition
	 * @return EditionEntity
	 * @throws InvalidArgumentException if ID is empty
	 */
	public function find($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("Empty id");
		}
		return Factory::edition()->fetchAndCreate($this->connection->dataSource("SELECT * FROM [edition] WHERE [id_edition] = %i", $id));
	}

	/**
	 * Find all editions
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [edition]");
	}

	/**
	 * Find all editions from given book
	 * @param \Leganto\DB\Book\Entity $book
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is not persisted
	 */
	public function findAllByBook(\Leganto\DB\Book\Entity $book) {
		if ($book->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The book entity has to be in state [persisted].");
		}
		return $this->connection->dataSource("SELECT * FROM [edition] WHERE [id_book_title] = %i", $book->getId());
	}

}

