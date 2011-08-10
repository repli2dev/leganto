<?php

/**
 * Followed selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Followed;

use Leganto\ORM\Workers\ISelector,
    InvalidArgumentException,
    Leganto\ORM\Exceptions\NotSupportedException;

class Selector implements ISelector {

	public function find($id) {
		throw new NotSupportedException();
	}

	/**
	 * Find all
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_my_following]");
	}

	/**
	 * Find all followed users by book
	 * @param IEntity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is empty
	 */
	public function findAllByBook($book) {
		if (empty($book)) {
			throw new InvalidArgumentException("The parameter [book] is empty.");
		}
		return $this->connection->dataSource("SELECT [view_my_following].* FROM [view_my_following] INNER JOIN [following] ON [following].[id_user_followed] = [view_my_following].[id_user] WHERE [id_book_title] = %i AND [following].[id_user] = %i", $book, System::user()->getId());
	}

	/**
	 * Find all opinion of followed users by book
	 * @param IEntity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is empty
	 */
	public function findAllOpinionByBook($book) {
		if (empty($book)) {
			throw new InvalidArgumentException("The parameter [book] is empty.");
		}
		return $this->connection->dataSource("SELECT [view_opinion].* FROM [view_opinion] INNER JOIN [following] ON [following].[id_user_followed] = [view_opinion].[id_user] WHERE [id_book_title] = %i AND [following].[id_user] = %i", $book, System::user()->getId());
	}

}