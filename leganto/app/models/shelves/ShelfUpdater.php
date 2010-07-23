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
 
class ShelfUpdater implements IUpdater
{

	public function changeOrder(ShelfEntity $shelf, BookEntity $book, $newOrder) {
		if (empty($newOrder)) {
			throw new NullPointerException("newOrder");
		}
		if ($newOrder <= 0) {
			throw new InvalidArgumentException("The new order has to be plus number.");
		}
		dibi::update("in_shelf", array("[order] = [order] + 1"))
			->where("[id_shelf] = %i", $shelf->getId())
			->where("[order] >= %i", $newOrder)
			->execute();
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
		SimpleTableModel::createTableModel("in_shelf")->deleteAll(array(
			"id_user"		=> $shelf->user,
			"id_book"		=> $book->bookNode
		));
		return SimpleTableModel::createTableModel("in_shelf")->insert(array(
			"id_shelf"		=> $shelf->getId(),
			"id_book"		=> $book->bookNode,
			"inserted"		=> new DateTime()
		));
	}

	/**
	 * Remove the book from user's shelfs
	 *
	 * @param UserEntity $user
	 * @param BookEntity $book
	 */
	public function removeFromShelves(UserEntity $user, BookEntity $book) {
		SimpleTableModel::createTableModel("in_shelf")->deleteAll(array(
			"id_user"		=> $user->getId(),
			"id_book"		=> $book->bookNode
		));
	}

	public function update(IEntity $entity) {
		return SimpleUpdater::createUpdater("shelf")->update($entity);
	}

}