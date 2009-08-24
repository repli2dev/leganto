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
class TagSelector implements ITagSelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [tag]");
	}
	
	/** @return DataSource */
	public function findAllByBook(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_book_tag]")
			->where("[id_book] = %i", $book->getId())
			->where("[id_language] = %i", $book->languageId);
	}

	/** @return TagEntity */
	public function find($id) {
		return Leganto::tags()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [tag] WHERE [id_tag] = %i", $id)
			);
	}
	
}