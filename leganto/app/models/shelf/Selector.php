<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Shelf;

use Leganto\ORM\Workers\ISelector,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException,
    Leganto\DB\Factory;

class Selector extends AWorker implements ISelector {

	/**
	 * Find all shelves
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_shelf]");
	}

	/**
	 * Find all shelves with certain book
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book has no ID
	 */
	public function findByBook(BookEntity $book) {
		if ($book->getId() == NULL) {
			throw new InvalidArgumentException("The book has no ID");
		}
		return $this->connection->dataSource("SELECT * FROM [view_shelf] WHERE [id_book_title] = %i", $book->getId());
	}

	/**
	 * Find all shelves which belong to certain user
	 * @param \Leganto\DB\User\Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user has no ID
	 */
	public function findByUser(\Leganto\DB\User\Entity $user) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("The user has no ID");
		}
		return $this->connection->dataSource("SELECT * FROM [view_shelf] WHERE [id_user] = %i", $user->getId());
	}

	/**
	 * Find all shelves of certaing user with certain book
	 * @param \Leganto\DB\User\Entity $user
	 * @param \Leganto\DB\Book\Entity $book
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book or user has no ID
	 */
	public function findByUserAndBook(\Leganto\DB\User\Entity $user, \Leganto\DB\Book\Entity $book) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("The user has no ID");
		}
		if ($book->getId() == NULL) {
			throw new InvalidArgumentException("The book has no ID");
		}
		// FIXME: Pohled view_shelf_book neni pripraven na to, aby se z nej delala entita Shelf
		return $this->connection->dataSource("SELECT * FROM [view_shelf_book] WHERE [id_user] = %i", $user->getId(), " AND [id_book_title] = %i", $book->getId());
	}

	/**
	 * Find one shelf of given id
	 * @param int $id id of shelf
	 * @return ShelfEntity
	 */
	public function find($id) {
		return Factory::shelf()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_shelf] WHERE [id_shelf] = %i", $id)
		);
	}

}
