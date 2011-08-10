<?php

/**
 * Opinion selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Opinion;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\SimpleEntityFactory,
    Leganto\System,
    InvalidArgumentException,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {

	/**
	 * Find all opinion
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_opinion]");
	}

	/** @return \Leganto\DB\Opinion\Entity */
	public function find($id) {
		return Factory::opinion()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_opinion] WHERE [id_opinion] = %i", $id)
		);
	}

	/**
	 * Find certain opinion on specific book of specified user
	 * @param \Leganto\DB\Book\Entity $book
	 * @param \Leganto\DB\User\Entity $user
	 * @return OpinionEntity
	 */
	public function findByBookAndUser($book, $user) {
		return Factory::opinion()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_opinion] WHERE [id_book_title] = %i", $book->getId(), " AND [id_user] = %i", $user->getId())
		);
	}

	/**
	 * Find all opinions belonging to certain book
	 * @param \Leganto\DB\Book\Entity $book
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is empty or do not have id.
	 */
	public function findAllByBook(\Leganto\DB\Book\Entity $book, \Leganto\DB\User\Entity $user = NULL) {
		if (empty($book)) {
			throw new InvalidArgumentException("Empty book.");
		}
		if ($book->getId() == NULL) {
			throw new InvalidArgumentException("Empty book id.");
		}
		// FIXME: odstranit zavislost na tride System
		return $this->connection->dataSource("SELECT * FROM [view_opinion] WHERE [id_book_title] = %i", $book->getId(), " AND [id_language] = %i", System::domain()->idLanguage);
		/*
		  It doesn't work
		  if (!empty($user) && $user->getId() == NULL) {
		  throw new InvalidArgumentException("user:id");
		  }

		  // In case that user does not have any opinions cause the same view as for unregistered user
		  if (isSet($user) && count($this->findAllByUser($user)) == 0) {
		  unset($user);
		  }
		  if (empty($user)) {
		  return dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_book_title] = %i", $book->getId(), " AND [id_language] = %i", $book->languageId);
		  } else {
		  return dibi::dataSource("SELECT * FROM [view_similar_opinion] WHERE [id_book_title] = %i", $book->getId(), " AND [id_language] = %i", $book->languageId, " AND [id_user_from] = %i", $user->getId());
		  }

		 */
	}

	/**
	 * Find all opinion of certain user
	 * @param UserEntity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user is empty or do not have id
	 */
	public function findAllByUser(\Leganto\DB\User\Entity $user, $empty = TRUE) {
		if (empty($user)) {
			throw new InvalidArgumentException("Empty user.");
		}
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("Empty user id.");
		}
		if ($empty) {
			return $this->connection->dataSource("SELECT * FROM [view_opinion] WHERE [id_user] = %i", $user->getId());
		} else {
			return $this->connection->dataSource("SELECT * FROM [view_opinion] WHERE [id_user] = %i AND [content] IS NOT NULL AND LENGTH(TRIM([content])) > 0", $user->getId());
		}
	}

	/**
	 * Find last (limit) non empty opinions
	 * @param int $limit limit of opinions
	 * @return DibiDataSource
	 */
	public function findAllNotEmptyLastUniqueBook($limit = 6) {
		return $this->connection->dataSource(
				"SELECT *
			 FROM [view_opinion]
			 WHERE [content] IS NOT NULL
			 AND LENGTH(TRIM([content])) > 0
			 GROUP BY [id_book_title]
			 ORDER BY [inserted] DESC
		     LIMIT %i", $limit
		);
	}

}
