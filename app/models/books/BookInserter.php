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
class BookInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(IEntity &$entity) {
		// crete book node
		$bookId = SimpleTableModel::createTableModel("book")->insert(array("inserted" => new DibiVariable("now()",'sql')));
		// create real book
		$entity->bookNode = $bookId;
		$bookTitleId = SimpleTableModel::createTableModel("book_title")->insert($this->getArrayFromEntity($entity, "Save"));

		$entity->setId($bookId);
		return $bookId;
	}
	
}