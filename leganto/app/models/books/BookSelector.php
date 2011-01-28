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
class BookSelector implements ISelector {
	const BOOK = 'book';

	const TAG = 'name';

	const AUTHOR = 'author';

	/* PUBLIC METHODS */

	/**
	 * Find all books
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_book]");
	}

	/**
	 * Find all books written by certain author
	 * @param AuthorEntity $author
	 * @return DibiDataSource
	 */
	public function findAllByAuthor(AuthorEntity $author) {
		if (empty($author)) {
			throw new NullPointerException("author");
		}
		if ($author->getId() == NULL) {
			throw new NullPointerException("author::id");
		}
		return dibi::dataSource("SELECT * FROM [view_author_book] WHERE [id_author] = %i", $author->getId())
			->orderBy(array("id_book", "title"));
	}

	/**
	 * Find all books in certain shelf
	 * @param ShelfEntity $shelf
	 * @return DibiDataSource
	 */
	public function findAllByShelf(ShelfEntity $shelf) {
		if (empty($shelf)) {
			throw new NullPointerException("shelf");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_shelf] = %i", $shelf->getId());
	}

	/**
	 * Find all books in shelves of certain user
	 * @param UserEntity $user
	 * @return DibiDataSource
	 */
	public function findAllInShelvesByUser(UserEntity $user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_user] = %i", $user->getId());
	}

	/**
	 * Find all related book
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
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

	/**
	 * Find all similar books
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
	public function findAllSimilar(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		if ($book->bookNode == NULL) {
			throw new NullPointerException("book::bookNode");
		}
		return dibi::dataSource("SELECT * FROM [view_similar_book] WHERE [id_book_from] = %i", $book->bookNode);
	}

	/**
	 * Find all books ordered by rating and number of readers
	 * @return DibiDataSource
	 */
	public function findAllTop() {
		return dibi::dataSource("SELECT * FROM [view_book] ORDER BY ROUND([rating]) DESC, [number_of_readers] DESC");
	}

	/**
	 * Search for given keyword(s)
	 * @param <type> $keyword
	 * @return DibiDataSource
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		foreach ($keywords AS $word) {
			if (!empty($conditions)) {
				$conditions .= " AND ";
			}
			$word = "%" . mysql_escape_string($word) . "%";
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

	/**
	 * Search for books which starts with given keywords
	 * @param <type> $keyword
	 * @return DibiDataSource
	 */
	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$word = mysql_escape_string($keyword) . "%";
		$conditions = "
			[title] LIKE '$word'
		";
		// Do not use view (because view takes over 100 ms)
		return dibi::dataSource("SELECT * FROM [book_title] WHERE " . $conditions . "");
	}

	/**
	 * Search on certain collumn
	 * @param string $column
	 * @param string $keyword
	 * @return DibiDataSource
	 */
	public function searchByColumn($column, $keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		switch ($column) {
			case self::BOOK:
				foreach ($keywords AS $word) {
					if (!empty($conditions)) {
						$conditions .= " AND ";
					}
					$word = "%" . mysql_escape_string($word) . "%";
					$conditions .= "
					([title] LIKE '$word' OR
					[subtitle] LIKE '$word')";
				}
				break;
			case self::TAG:
				foreach ($keywords AS $word) {
					if (!empty($conditions)) {
						$conditions .= " AND ";
					}
					$word = "%" . mysql_escape_string($word) . "%";
					$conditions .= "
					([name] LIKE '$word')";
				}
				break;
			case self::AUTHOR:
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

	/**
	 * Recommended books
	 *    * Not to recommend book that user have read.
	 *    * Books from most similar users
	 *    * Good books (rating 4-5)
	 * TODO: User should have option to say Never show again
	 */
	 public function findRecommendedBook() {
		 $user = System::user()->getId();
		 // MySQL do not support EXCEPT and LIMIT in subquery
		 $result = dibi::query("
			SELECT id_book_title FROM opinion INNER JOIN (
				SELECT id_user_to FROM user_similarity WHERE id_user_from = %i ",$user," ORDER BY value DESC LIMIT 3
			) most ON id_user = id_user_to
			WHERE id_book_title NOT IN (
				SELECT id_book_title FROM opinion WHERE id_user = %i ",$user,"
			) AND rating BETWEEN 4 AND 5
			ORDER BY RAND() LIMIT 1");
		 if($result->count() == 0) {
			 return;
		 }
		 return $this->findAll()->where("[id_book_title] IN %l",$result->fetchAll());
	 }

	 public function findRandom() {
		return dibi::query("SELECT id_book_title FROM view_book ORDER BY RAND() LIMIT 1")->fetch();
	 }

}