<?php

/**
 * Book inserter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Book;

use Leganto\ORM\Workers\IInserter,
    Leganto\ORM\SimpleTableModel,
    Leganto\ORM\IEntity,
    InvalidArgumentException,
    Leganto\ORM\Workers\AWorker,
    Nette\DateTime;

class Inserter extends AWorker implements IInserter {
	/* PUBLIC METHODS */

	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		if ($entity->bookNode == NULL) {
			// crete book node
			$bookId = SimpleTableModel::createTableModel("book", $this->connection)->insert(array("inserted" => new DateTime()));
			// create real book
			$entity->bookNode = $bookId;
		}
		$bookTitleId = SimpleTableModel::createTableModel("book_title", $this->connection)->insert($entity->getData("Save"));

		return $bookTitleId;
	}

}