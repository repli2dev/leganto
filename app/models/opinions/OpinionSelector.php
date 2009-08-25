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

	/**
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
	public function findAllByBook(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_book] = %i", $book->bookNode, " AND [id_language] = %i", $book->languageId)
				->orderBy("inserted", "desc");

	}


	/** @return BookEntity */
	public function find($id) {
		return Leganto::opinions()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_opinion] = %i", $id)
			);
	}

}
