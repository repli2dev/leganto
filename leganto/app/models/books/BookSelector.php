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
class BookSelector implements ISelector
{
	const BOOK = 'book';

	const TAG = 'name';

	const AUTHOR = 'author';

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_book]");
	}
	
	public function findAllByAuthor(AuthorEntity $author) {
		if (empty($author)) {
			throw new NullPointerException("author");
		}
		if ($author->getId() == NULL) {
			throw new NullPointerException("author::id");
		}
		return dibi::dataSource("SELECT * FROM [view_author_book] WHERE [id_author] = %i", $author->getId())
				->orderBy(array("id_book","title"));
	}

	public function findAllByShelf(ShelfEntity $shelf) {
		if (empty($shelf)) {
			throw new NullPointerException("shelf");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_shelf] = %i", $shelf->getId());
	}

	public function findAllInShelvesByUser(UserEntity $user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_user] = %i", $user->getId());
	}

	public function findAllRelated(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		if ($book->bookNode == NULL) {
			throw new NullPointerException("book::bookNode");
		}
		if ($book->getId() == NULL) {
			throw new NullPointerException("book::id");
		}
		return dibi::dataSource("
		    SELECT * FROM [view_book]
		    WHERE [id_book] = %i ", $book->bookNode,
		    "AND [id_book_title] != %i ", $book->getId(),
		    "ORDER BY [title]"
		);
	}

	public function findAllSimilar(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		if ($book->bookNode == NULL) {
			throw new NullPointerException("book::bookNode");
		}
		return dibi::dataSource("SELECT * FROM [view_similar_book] WHERE [id_book_from] = %i", $book->bookNode);
	}

	public function findAllTop() {
		return dibi::dataSource("SELECT * FROM [view_book] ORDER BY ROUND([rating]) DESC, [number_of_readers] DESC");
	}

	public function search($keyword) {
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
			([title] LIKE '$word' OR
			[subtitle] LIKE '$word' OR
			[name] LIKE '$word' OR
			[first_name] LIKE '$word' OR
			[last_name] LIKE '$word' OR
			[group_name] LIKE '$word')";
		}
		return dibi::dataSource("SELECT * FROM [view_book_search] " . (empty($conditions) ? "" : " WHERE " . $conditions) . " GROUP BY [id_book_title]");
	}

	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$word = "%".mysql_escape_string($keyword)."%";
		$conditions = "
			[title] LIKE '$word'
		";
		// Do not use view (because view takes over 100 ms)
		return dibi::dataSource("SELECT * FROM [book_title] WHERE " . $conditions . "");
	}

	public function searchByColumn($column,$keyword) {
		if(empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		switch($column) {
			case self::BOOK:
				foreach($keywords AS $word) {
					if (!empty($conditions)) {
						$conditions .= " AND ";
					}
					$word = "%".mysql_escape_string($word)."%";
					$conditions .= "
					([title] LIKE '$word' OR
					[subtitle] LIKE '$word')";
				}
				break;
			case self::TAG:
				foreach($keywords AS $word) {
					if (!empty($conditions)) {
						$conditions .= " AND ";
					}
					$word = "%".mysql_escape_string($word)."%";
					$conditions .= "
					([name] LIKE '$word')";
				}
				break;
			case self::AUTHOR:
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
				break;
			default:
				throw new InvalidArgumentException("column");
		}
		return dibi::dataSource("SELECT * FROM [view_book_search] " . (empty($conditions) ? "" : " WHERE " . $conditions) . " GROUP BY [id_book]");
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

}