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
class OpinionSelector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all opinion
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_opinion]");
	}

	/** @return OpinionEntity */
	public function find($id) {
		return Leganto::opinions()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_opinion] = %i", $id)
		);
	}

	/**
	 * Find certain opinion on specific book of specified user
	 * @param BookEntity $book
	 * @param UserEntity $book
	 * @return OpinionEntity
	 */
	public function findByBookAndUser($book, $user) {
		return Leganto::opinions()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_book_title] = %i", $book->getId(), " AND [id_user] = %i", $user->getId())
		);
	}

	/**
	 * Find all opinions belonging to certain book
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
	public function findAllByBook(BookEntity $book, UserEntity $user = NULL) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		if ($book->getId() == NULL) {
			throw new NullPointerException("book:id");
		}
		return dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_book_title] = %i", $book->getId(), " AND [id_language] = %i", $book->languageId);
/*
		It doesn't work
		if (!empty($user) && $user->getId() == NULL) {
			throw new NullPointerException("user:id");
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
	 */
	public function findAllByUser(UserEntity $user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_user] = %i", $user->getId());
	}

	/**
	 * Find last (limit) non empty opinions
	 * @param int $limit limit of opinions
	 * @return DibiDataSource
	 */
	public function findAllNotEmptyLast($limit = 6) {
		return dibi::dataSource(
			"SELECT *
		 FROM [view_opinion]
		 WHERE [content] IS NOT NULL
		 AND LENGTH(TRIM([content])) > 0
		 ORDER BY [inserted] DESC
		 LIMIT %i", $limit);
	}

}
