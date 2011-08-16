<?php

/**
 * Shelf updater
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Shelf;

use Leganto\ORM\Workers\IUpdater,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\SimpleTableModel,
    InvalidArgumentException,
    Leganto\ORM\IEntity,
    Nette\DateTime,
    Leganto\ORM\Workers\SimpleUpdater;

class Updater extends AWorker implements IUpdater {

	/**
	 * Change order of book in shelf.
	 * @param \Leganto\DB\Shelf\Entity $shelf
	 * @param \Leganto\DB\Book\Entity $book
	 * @param int $newOrder 
	 * @throws InvalidArgumentException if newOrder is lower than zero
	 */
	public function changeOrder(\Leganto\DB\Shelf\Entity $shelf, \Leganto\DB\Book\Entity $book, $newOrder) {
		if ($newOrder < 0) {
			throw new InvalidArgumentException("The new order has to be non negative number.");
		}
		// Get old order
		$oldOrder = $this->connection->dataSource("
		    SELECT [order]
		    FROM [in_shelf]
		    WHERE [id_shelf] = %i ", $shelf->getId(), "AND [id_book_title] = %i", $book->getId()
			)->fetch()->order;
		if ($oldOrder > $newOrder) {
			$this->connection->query("
			UPDATE [in_shelf] SET [order] = [order] + 1
			WHERE [id_shelf] = %i ", $shelf->getId(), "AND [order] >= %i ", $newOrder, "AND [order] < %i", $oldOrder
			);
		} else if ($oldOrder < $newOrder) {
			$this->connection->query("
			UPDATE [in_shelf] SET [order] = [order] -1
			WHERE [id_shelf] = %i ", $shelf->getId(), "AND [order] <= %i ", $newOrder, "AND [order] > %i", $oldOrder
			);
		}
		$this->connection->update("in_shelf", array("order" => $newOrder))
			->where("[id_book_title] = %i", $book->getId())
			->where("[id_shelf] = %i", $shelf->getId())
			->execute();
	}

	/**
	 * Insert the book to the shelf and remove it from old shelves.
	 *
	 * @param \Leganto\DB\Shelf\Entity $shelf
	 * @param \Leganto\DB\Book\Entity $book
	 * @return int Inserted ID. If the book has been already inserted, it returns -1.
	 */
	public function insertToShelf(\Leganto\DB\Shelf\Entity $shelf, \Leganto\DB\Book\Entity $book) {
		return SimpleTableModel::createTableModel("in_shelf", $this->connection)->insert(array(
			    "id_shelf" => $shelf->getId(),
			    "id_book_title" => $book->getId(),
			    "order" => $this->getNumberOfBooksInShelf($shelf),
			    "inserted" => new DateTime()
			));
	}

	/**
	 * Remove the book from shelf
	 *
	 * @param \Leganto\DB\Shelf\Entity shelf
	 * @param \Leganto\DB\Book\Entity  book
	 */
	public function removeBookFromShelf(\Leganto\DB\Shelf\Entity $shelf, \Leganto\DB\Book\Entity $book) {
		// Move the book on the bottom of shelf
		$this->changeOrder($shelf, $book, PHP_INT_MAX);
		SimpleTableModel::createTableModel("in_shelf", $this->connection)->deleteAll(array(
		    "id_shelf" => $shelf->getId(),
		    "id_book_title" => $book->getId()
		));
	}

	/**
	 * Remove book with bookTitleId from userId shelves "Wanted" a "Reading just now"
	 * @param int $bookTitleId
	 * @param int $userId 
	 */
	public function removeReadBookFromShelves($bookTitleId, $userId) {
		$shelves = $this->connection->query("
            SELECT [id_shelf]
            FROM [shelf]
            WHERE [id_user] = %i", $userId, " AND [type] IN ('wanted', 'reading')"
			)->fetchPairs("id_shelf", "id_shelf");
		if (!empty($shelves)) {
			$this->connection->query("
				DELETE FROM [in_shelf] WHERE [id_shelf] IN %l", $shelves, " AND [id_book_title] = %i", $bookTitleId
			);
		}
	}

	public function update(IEntity $entity) {
		return SimpleUpdater::createUpdater("shelf", $this->connection)->update($entity);
	}

	/**
	 * Get number of books in shelf
	 * @param \Leganto\DB\Shelf\Entity $shelf
	 * @return type 
	 */
	private function getNumberOfBooksInShelf(\Leganto\DB\Shelf\Entity $shelf) {
		return $this->connection->query("
		SELECT COUNT([id_in_shelf]) AS number
		FROM [in_shelf]
		WHERE [id_shelf] = %i", $shelf->getId()
			)->fetch()->number;
	}

}