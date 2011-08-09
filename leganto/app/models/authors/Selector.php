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
namespace Leganto\DB\Author;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	dibi;

class Selector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all authors
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_author]");
	}

	/**
	 * Find all authors of certain books
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
	public function findAllByBook(\Leganto\DB\Book\Entity $book) {
		if (empty($book)) {
			throw new \InvalidArgumentException("book");
		}
		return dibi::dataSource("
			SELECT * FROM [view_book_author]
			WHERE [id_book] = %i", $book->bookNode
		);
	}

	/**
	 * Find all authors by given book title id
	 * @param int $id
	 * @return DibiDataSource
	 */
	public function findAllByBookTitleId($id) {
		if (empty($id)) {
			throw new \InvalidArgumentException("id");
		}
		return dibi::dataSource("
			SELECT * FROM [view_book_search]
			WHERE [id_book_title] = %i GROUP BY [id_author]", $id
		);
	}

	/**
	 * 
	 * @param object $books books ids
	 * @return DibiDataSource
	 */
	public function findAllByBooks(array $books) {
		if (empty($books)) {
			throw new \InvalidArgumentException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_book_author]")
			->where("[id_book] IN %l", $books);
	}

	/** @return AuthorEntity */
	public function find($id) {
		return Factory::authors()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_author] WHERE [id_author] = %i", $id)
		);
	}

	/**
	 * Search authors for given keyword(s)
	 * @param string $keyword
	 * @return DibiDataSource
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new \InvalidArgumentException("keyword");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		foreach ($keywords AS $word) {
			if (!empty($conditions)) {
				$conditions .= " AND ";
			}
			$word = "%" . mysql_escape_string($word) . "%";
			$conditions .= "
			([first_name] LIKE '$word' OR
			[last_name] LIKE '$word' OR
			[group_name] LIKE '$word')";
		}
		return dibi::dataSource("SELECT * FROM [view_author] " . (empty($conditions) ? "" : " WHERE " . $conditions) . " GROUP BY [id_author]");
	}

	/**
	 * Search for authors starting with given keyword
	 * @param string $keyword
	 * @return DibiDataSource
	 */
	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new \InvalidArgumentException("keyword");
		}
		$word = mysql_escape_string($keyword) . "%";
		$conditions .= "
			([first_name] LIKE '$word' OR
			[last_name] LIKE '$word' OR
			[group_name] LIKE '$word')";
		// Do not use view (because view takes over 100 ms)
		return dibi::dataSource("SELECT * FROM [view_author] WHERE " . $conditions . "");
	}

}