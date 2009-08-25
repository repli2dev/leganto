<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class BookSelector extends Worker implements IBookSelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_book]");
	}
	
	public function findAllByAuthor(IEntity $author) {
		if (empty($author)) {
			throw new NullPointerException("author");
		}
		return dibi::dataSource("SELECT * FROM [view_author_book] WHERE [id_author] = %i", $author->getId())
				->orderBy(array("id_book","title"));
	}

	public function findOthers(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return $this->findAll()
			->where("[id_book] = %i", $book->bookNode)
			->where("[id_book_title] != %i", $book->getId())
			->orderBy("title");

	}

	/** @return BookEntity */
	public function find($id) {
		return Leganto::books()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_book] WHERE [id_book_title] = %i", $id)
			);
	}

	public function search($query) {
		// TODO
	}

}