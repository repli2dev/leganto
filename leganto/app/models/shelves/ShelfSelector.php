<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class ShelfSelector implements ISelector {

	/**
	 * Find all shelves
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_shelf]");
	}

	/**
	 * Find all shelves with certain book
	 * @return DibIDataSource
	 */
	public function findByBook(BookEntity $book) {
		if ($book->getId() == NULL) {
			throw new NullPointerException("The book has no ID");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf] WHERE [id_book] = %i", $book->getId());
	}

	/**
	 * Find all shelves which belong to certain user
	 * @param UserEntity $user
	 * @return DibiDataSource
	 */
	public function findByUser(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("The user has no ID");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf] WHERE [id_user] = %i", $user->getId());
	}

	/**
	 * Find all shelves of certaing user with certain book
	 * @param UserEntity $user
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
	public function findByUserAndBook(UserEntity $user, BookEntity $book) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("The user has no ID");
		}
		if ($book->getId() == NULL) {
			throw new NullPointerException("The book has no ID");
		}
		// FIXME: Pohled view_shelf_book neni pripraven na to, aby se z nej delala entita Shelf
		return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_user] = %i", $user->getId(), " AND [id_book_title] = %i", $book->getId());
	}

	/**
	 * Find one shelf of given id
	 * @param int $id id of shelf
	 * @return ShelfEntity
	 */
	public function find($id) {
		return Leganto::shelves()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_shelf] WHERE [id_shelf] = %i", $id)
		);
	}

}
