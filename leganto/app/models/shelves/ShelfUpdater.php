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
class ShelfUpdater implements IUpdater {

	public function changeOrder(ShelfEntity $shelf, BookEntity $book, $newOrder) {
		if ($newOrder < 0) {
			throw new InvalidArgumentException("The new order has to be non negative number.");
		}
		// Get old order
		$oldOrder = dibi::dataSource("
		    SELECT [order]
		    FROM [in_shelf]
		    WHERE [id_shelf] = %i ", $shelf->getId(),
						"AND [id_book] = %i", $book->getId()
				)->fetch()->order;
		if ($oldOrder > $newOrder) {
			dibi::query("
			UPDATE [in_shelf] SET [order] = [order] + 1
			WHERE [id_shelf] = %i ", $shelf->getId(),
							"AND [order] >= %i ", $newOrder,
							"AND [order] < %i", $oldOrder
			);
		} else if ($oldOrder < $newOrder) {
			dibi::query("
			UPDATE [in_shelf] SET [order] = [order] -1
			WHERE [id_shelf] = %i ", $shelf->getId(),
							"AND [order] <= %i ", $newOrder,
							"AND [order] > %i", $oldOrder
			);
		}
		dibi::update("in_shelf", array("order" => $newOrder))
				->where("[id_book] = %i", $book->bookNode)
				->where("[id_shelf] = %i", $shelf->getId())
				->execute();
	}

	/**
	 * Insert the book to the shelf and remove it from old shelves.
	 *
	 * @param ShelfEntity $shelf
	 * @param BookEntity $book
	 * @return int Inserted ID. If the book has been already inserted, it returns -1.
	 */
	public function insertToShelf(ShelfEntity $shelf, BookEntity $book) {
		return SimpleTableModel::createTableModel("in_shelf")->insert(array(
			"id_shelf" => $shelf->getId(),
			"id_book" => $book->bookNode,
			"order" => $this->getNumberOfBooksInShelf($shelf),
			"inserted" => new DateTime()
		));
	}

	/**
	 * Remove the book from shelf
	 *
	 * @param ShelfEntity shelf
	 * @param BookEntity  book
	 */
	public function removeBookFromShelf(ShelfEntity $shelf, BookEntity $book) {
		// Move the book on the bottom of shelf
		$this->changeOrder($shelf, $book, PHP_INT_MAX);
		SimpleTableModel::createTableModel("in_shelf")->deleteAll(array(
			"id_shelf" => $shelf->getId(),
			"id_book" => $book->getId()
		));
	}

	public function removeReadBookFromShelves($bookTitleId, $userId) {
		$shelves = dibi::query("
            SELECT [id_shelf]
            FROM [shelf]
            WHERE [id_user] = %i", $userId,
			" AND [type] IN ('wanted', 'reading')"
		)->fetchPairs("id_shelf", "id_shelf");
		if (!empty($shelves)) {
			dibi::query("
				DELETE FROM [in_shelf] WHERE [id_shelf] IN %l", $shelves,
				" AND [id_book_title] = %i", $bookTitleId
			);
		}
	}

	public function update(IEntity $entity) {
		return SimpleUpdater::createUpdater("shelf")->update($entity);
	}

	private function getNumberOfBooksInShelf(ShelfEntity $shelf) {
		return dibi::query("
		SELECT COUNT([id_in_shelf]) AS number
		FROM [in_shelf]
		WHERE [id_shelf] = %i", $shelf->getId()
		)->fetch()->number;
	}

}