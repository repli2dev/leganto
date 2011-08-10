<?php

/**
 * Topic selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Topic;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {

	/**
	 * Find one certain topic by id
	 * @param int $id id of topic
	 * @return TopicEntity
	 * @throws InvalidArgumentException if id is empty
	 */
	public function find($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("Empty id.");
		}
		return Factory::topic()->fetchAndCreate($this->connection->dataSource("SELECT * FROM [view_topic] WHERE [id_topic] = %i", $id));
	}

	/**
	 * Find all topics
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_topic]");
	}

}
