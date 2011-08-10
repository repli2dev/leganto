<?php

/**
 * Feed selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Feed;

use Leganto\ORM\Workers\ISelector,
    Leganto\ORM\IEntity,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {

	public function find($id) {
		return Factory::feed()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_feed_event] WHERE [id_feed_event] = %i", $id)
		);
	}

	/**
	 * Find all feed items
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_feed_event]");
	}

	/**
	 * Find all feed items from specific user
	 * @param IEntity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user is empty or has no id
	 */
	public function findAllByUser(IEntity $user) {
		if (empty($user)) {
			throw new InvalidArgumentException("The parameter [user] is empty.");
		}
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("The user has no ID.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_feed_event] WHERE [id_user] = %i", $user->getId());
	}

}