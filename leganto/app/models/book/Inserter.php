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
namespace Leganto\DB\Book;
use Leganto\ORM\Workers\IInserter,
	Leganto\DB\Factory,
	Leganto\ORM\SimpleTableModel,
	\dibi as dibi;

class Inserter implements IInserter {
	/* PUBLIC METHODS */

	public function insert(\Leganto\ORM\IEntity &$entity) {
		if ($entity->getState() != \Leganto\ORM\IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		if ($entity->bookNode == NULL) {
			// crete book node
			$bookId = SimpleTableModel::createTableModel("book")->insert(array("inserted" => new DateTime()));
			// create real book
			$entity->bookNode = $bookId;
		}
		$bookTitleId = SimpleTableModel::createTableModel("book_title")->insert($entity->getData("Save"));

		return $bookTitleId;
	}

}