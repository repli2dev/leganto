<?php

class EditionSelector implements ISelector
{

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		Leganto::editions()->fetchAndCreate(dibi::dataSource("SELECT * FROM [edition] WHERE [id_edition] = %i", $id));
	}

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [edition]");
	}

	/** @return DibiDataSource */
	public function findAllByBook(BookEntity $book) {
		if ($book->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The book entity has to be in state [persisted].");
		}
		return dibi::dataSource("SELECT * FROM [edition] WHERE [id_book_title] = %i", $book->getId());
	}

}

