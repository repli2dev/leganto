<?php

/**
 * Discussion selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Discussion;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {

	/**
	 * Find one discussion with certain id
	 * @param int $id id of discussion
	 * @return DiscussionEntity
	 * @throws InvalidArgumentException if ID is empty
	 */
	public function find($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("Empty id.");
		}
		return Factory::discussion()->fetchAndCreate($this->findAll()->where("[id_discussion] = %i", $id));
	}

	/**
	 * Find all discussions
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_discussion]");
	}

	/**
	 * Find discussion according to discussed id and type
	 * @param int $discussed
	 * @param int $type
	 * @return DiscussionEntity
	 * @throws InvalidArgumentException ifdiscussed or type is empty
	 */
	public function findByDiscussedAndType($discussed, $type) {
		if (empty($discussed)) {
			throw new InvalidArgumentException("Empty discussed.");
		}
		if (empty($type)) {
			throw new InvalidArgumentException("Empty type.");
		}
		return Factory::discussion()->fetchAndCreate($this->findAll()->where("[id_discussed] = %i", $discussed, " AND [id_discussable] = %i", $type));
	}

}
