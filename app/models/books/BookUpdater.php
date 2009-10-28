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
class BookUpdater extends Worker implements IUpdater
{

	/* PUBLIC METHODS */
	
	public function update(IEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		SimpleTableModel::createTableModel("book_title")->update($entity->getId(), $input);
	}

	public function setWrittenBy(BookEntity $book, $authors) {
		if (!$book->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		// delete all old relations between the book and authors
		dibi::delete("written_by")->where("[id_book] = %i",$book->bookNode)->execute();
		// add new relations
		foreach($authors as $author){
			dibi::insert("written_by",
				array(
					"id_book" => $book->bookNode,
					"id_author" => $author->getId()
				)
			)->execute();
		}
	}

	/**
	 * It tags a book
	 *
	 * @param int $book Book ID
	 * @param int $tag Tag ID
	 */
	public function setTagged(BookEntity $book, $tag) {
		$rows = SimpleTableModel::createTableModel("tagged")
			->findAll()
			->where("[id_book] = %i", $book)
			->where("[id_tag] = %i", $tag);
		if ($rows->count() == 0) {
			SimpleTableModel::createTableModel("tagged")->insert(array(
				"id_book" => $book,
				"id_tag" => $tag
			));
		}
	}

}