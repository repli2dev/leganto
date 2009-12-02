<?php
/**
 * @author Jan Papousek
 */
class OpinionSelector implements ISelector
{

	/* PUBLIC METHODS */

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
		if (!empty($user) && $user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		if (empty($user)) {
			return dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_book] = %i", $book->bookNode, " AND [id_language] = %i", $book->languageId);
		}
		else {
			return dibi::dataSource("SELECT * FROM [view_similar_opinion] WHERE [id_book] = %i", $book->bookNode, " AND [id_language] = %i", $book->languageId, " AND [id_user_from] = %i", $user->getId());
		}

	}

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
