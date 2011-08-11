<?php

/**
 * Book selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Book;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {
	const BOOK = 'book';

	const TAG = 'name';

	const AUTHOR = 'author';

	/**
	 * Find all books
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_book]");
	}

	/**
	 * Find all books written by certain author
	 * @param \Leganto\DB\Author\Entity $author
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if author is empty or do not have id
	 */
	public function findAllByAuthor(\Leganto\DB\Author\Entity $author) {
		if (empty($author)) {
			throw new InvalidArgumentException("Empty author.");
		}
		if ($author->getId() == NULL) {
			throw new InvalidArgumentException("Empty author id.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_author_book] WHERE [id_author] = %i", $author->getId())
				->orderBy(array("id_book", "title"));
	}

	/**
	 * Find all books in certain shelf
	 * @param \Leganto\DB\Shelf\Entity $shelf
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if shelf is empty
	 */
	public function findAllByShelf(\Leganto\DB\Shelf\Entity $shelf) {
		if (empty($shelf)) {
			throw new InvalidArgumentException("Empty shelf.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_shelf_book] WHERE [id_shelf] = %i", $shelf->getId());
	}

	/**
	 * Find all books in shelves of certain user
	 * @param \Leganto\DB\User\Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user is empty
	 */
	public function findAllInShelvesByUser(\Leganto\DB\User\Entity $user) {
		if (empty($user)) {
			throw new InvalidArgumentException("Empty user.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_shelf_book] WHERE [id_user] = %i", $user->getId());
	}

	/**
	 * Find all related book
	 * @param \Leganto\DB\Book\Entity $book
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is empty or bookNode or id is not set.
	 */
	public function findAllRelated(\Leganto\DB\Book\Entity $book) {
		if (empty($book)) {
			throw new InvalidArgumentException("Empty book.");
		}
		if ($book->bookNode == NULL) {
			throw new InvalidArgumentException("Empty bookNode.");
		}
		if ($book->getId() == NULL) {
			throw new InvalidArgumentException("Empty book id.");
		}
		return $this->connection->dataSource("
		    SELECT * FROM [view_book]
		    WHERE [id_book] = %i ", $book->bookNode, "AND [id_book_title] != %i ", $book->getId(), "ORDER BY [title]"
		);
	}

	/**
	 * Find all similar books
	 * @param BookEntity $book
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if book is empty or bookNode is not set.
	 */
	public function findAllSimilar(\Leganto\DB\Book\Entity $book) {
		if (empty($book)) {
			throw new InvalidArgumentException("Empty book.");
		}
		if ($book->bookNode == NULL) {
			throw new InvalidArgumentException("Empty bookNode.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_similar_book] WHERE [id_book_from] = %i", $book->bookNode);
	}

	/**
	 * Find all books ordered by rating and number of readers
	 * @return DibiDataSource
	 */
	public function findAllTop() {
		return $this->connection->dataSource("SELECT * FROM [view_book] ORDER BY ROUND([rating]) DESC, [number_of_readers] DESC");
	}

	/**
	 * Search for given keyword(s)
	 * @param string $keyword
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if keyword is empty
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("Empty keyword.");
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
		return $this->connection->dataSource("SELECT * FROM [view_book_search] " . (empty($conditions) ? "" : " WHERE " . $conditions) . " GROUP BY [id_book_title]");
	}

	/**
	 * Search for books which starts with given keywords
	 * @param string $keyword
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if keyword is empty
	 */
	public function suggest($keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("Empty keyword.");
		}
		$word = mysql_escape_string($keyword) . "%";
		$conditions = "
			[title] LIKE '$word'
				";
		// Do not use view (because view takes over 100 ms)
		return $this->connection->dataSource("SELECT * FROM [book_title] WHERE " . $conditions . "");
	}

	/**
	 * Search on certain collumn
	 * @param string $column
	 * @param string $keyword
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if keyword is empty
	 */
	public function searchByColumn($column, $keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("Empty keyword.");
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
		return $this->connection->dataSource("SELECT * FROM [view_book_search] " . (empty($conditions) ? "" : " WHERE " . $conditions) . " GROUP BY [id_book]");
	}

	/**
	 * Fetch related books
	 * @param \Leganto\DB\Book\Entity $book
	 * @return DibiDataSource
	 */
	public function findOthers(\Leganto\DB\Book\Entity $book) {
		if (empty($book)) {
			throw new InvalidArgumentException("book");
		}
		return $this->findAll()
				->where("[id_book] = %i", $book->bookNode)
				->where("[id_book_title] != %i", $book->getId())
				->orderBy("title");
	}

	/** @return \Leganto\DB\Book\Entity */
	public function find($id) {
		return Factory::book()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_book] WHERE [id_book_title] = %i", $id)
		);
	}

	/**
	 * Recommended books
	 *    * Not to recommend book that user have read.
	 *    * Books from most similar users
	 *    * Good books (rating 4-5)
	 * TODO: User should have option to say Never show again
	 * @param \Leganto\DB\User\Entity $user
	 * @return DibiDataSource
	 */
	public function findRecommendedBook(\Leganto\DB\User\Entity $user) {
		$user = $user->getId();
		$result = $this->connection->dataSource("
			SELECT * FROM [tmp_recommended_book] WHERE [id_user] = %i
			ORDER BY RAND() LIMIT 1
		", $user);
//		// MySQL do not support EXCEPT and LIMIT in subquery
//		$result = $this->connection->query("
//			SELECT id_book_title FROM opinion INNER JOIN (
//				SELECT id_user_to FROM user_similarity WHERE id_user_from = %i ",$user," ORDER BY value DESC LIMIT 3
//			) most ON id_user = id_user_to
//			WHERE id_book_title NOT IN (
//				SELECT id_book_title FROM opinion WHERE id_user = %i ",$user,"
//			) AND rating BETWEEN 4 AND 5
//			ORDER BY RAND() LIMIT 2");
		if ($result->count() == 0) {
			return;
		}
		return $result;
//		return $this->findAll()->where("[id_book_title] IN %l",$result->fetchAll());
	}

	/**
	 * Find random book
	 * @return DibiRow
	 */
	public function findRandom() {
		return $this->connection->query("SELECT id_book_title FROM view_book ORDER BY RAND() LIMIT 1")->fetch();
	}

}