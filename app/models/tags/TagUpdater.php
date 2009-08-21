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
class TagUpdater extends Worker implements ITagUpdater
{

	/* PUBLIC METHODS */
	
	public function update(TagEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		SimpleTableModel::createTableModel("tag")->update($entity->getId(), $input);
	}

	/**
	 * It tags a book
	 *
	 * @param int $book Book ID
	 * @param int $tag Tag ID
	 */
	public function setTagged($book, $tag) {
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