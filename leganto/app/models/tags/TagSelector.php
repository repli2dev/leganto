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
class TagSelector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all tags
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [tag]");
	}

	/** @return DataSource */
	public function findAllByBook(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_book_tag]")
			->where("[id_book] = %i", $book->bookNode)
			->where("[id_language] = %i", $book->languageId)
			->orderBy("name");
	}

	/** @return TagEntity */
	public function find($id) {
		return Leganto::tags()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [tag] WHERE [id_tag] = %i", $id)
		);
	}

	/**
	 * Return all tags beggining with keyword
	 * @param string $keyword
	 * @return DibiDataSource
	 */
	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$word = "" . mysql_escape_string($keyword) . "%";
		$conditions = "
			[name] LIKE '$word'
		";
		// Do not use view (because view takes over 100 ms)
		return dibi::dataSource("SELECT * FROM [tag] WHERE " . $conditions . "");
	}

}