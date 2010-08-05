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

	public function search($keyword){
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		foreach($keywords AS $word) {
		    if (!empty($conditions)) {
			$conditions .= " AND ";
		    }
		    $word = "%".mysql_escape_string($word)."%";
		    $conditions .= "
			([first_name] LIKE '$word' OR
			[last_name] LIKE '$word' OR
			[group_name] LIKE '$word')";
		}
		return dibi::dataSource("SELECT * FROM [view_author] " . (empty($conditions) ? "" : " WHERE " . $conditions) . " GROUP BY [id_author]");
	}

	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$word = "%".mysql_escape_string($keyword)."%";
		$conditions .= "
			([first_name] LIKE '$word' OR
			[last_name] LIKE '$word' OR
			[group_name] LIKE '$word')";
		// Do not use view (because view takes over 100 ms)
		return dibi::dataSource("SELECT * FROM [view_author] WHERE " . $conditions . "");
	}

}