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
class AuthorSelector implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_author]");
	}
	
	public function findAllByBook(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("
			SELECT * FROM [view_book_author]
			WHERE [id_book] = %i", $book->bookNode
		);
	}
	
	/**
	 * 
	 * @param object $books books ids
	 * @return DibiDataSource
	 */
	public function findAllByBooks(array $books) {
		if (empty($books)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_book_author]")
			->where("[id_book] IN %l", $books);
	}

	/** @return AuthorEntity */
	public function find($id) {
		return Leganto::authors()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_author] WHERE [id_author] = %i", $id)
			);
	}

}