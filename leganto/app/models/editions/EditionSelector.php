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
class EditionSelector implements ISelector {

	/**
	 * Find one edition
	 * @param int $id id of edition
	 * @return EditionEntity
	 */
	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::editions()->fetchAndCreate(dibi::dataSource("SELECT * FROM [edition] WHERE [id_edition] = %i", $id));
	}

	/**
	 * Find all editions
	 * @return DibiDataSource
	 */
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

