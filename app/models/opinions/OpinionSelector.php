<?php
/**
 * @author Jan Papousek
 */
class OpinionSelector implements IOpinionSelector
{

	/* PUBLIC METHODS */

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_opinion]");
	}

	public function findAllByBook(IEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_book] = %i", $book->bookNode, " AND [id_language] = %i", $book->language)
				->orderBy(array("id_book","title"));
	}


	/** @return BookEntity */
	public function find($id) {
		return Leganto::opinions()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_opinion] WHERE [id_opinion] = %i", $id)
			);
	}

}
