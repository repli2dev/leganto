<?php

/**
 * Message selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Message;

use Leganto\ORM\Workers\ISelector,
    InvalidArgumentException,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {

	/**
	 * Return all entries
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_message]");
	}

	/**
	 * Return all messages send or receive by user
	 * @param \Leganto\DB\User\Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user id is empty.
	 */
	public function findAllWithUser(\Leganto\DB\User\Entity $user) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("Empty user id.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_message] WHERE ([id_user_from] = %i AND [deleted_by_owner] = 0) OR ([id_user_to] = %i AND [deleted_by_recipient] = 0) ORDER BY inserted DESC", $user->getId(), $user->getId());
	}

	/**
	 * Find all mesages to one specific user
	 * @param \Leganto\DB\User\Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user id is empty.
	 */
	public function findAllToUser(\Leganto\DB\User\Entity $user) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("Emptya user id.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_message] WHERE [id_user_to] = %i ORDER BY inserted DESC", $user->getId());
	}

	public function find($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("id");
		}
		return Factory::message()->fetchAndCreate($this->connection->DataSource("SELECT * FROM [view_message] WHERE [id_message] = %i", $id));
	}

	/**
	 * Return false if user has some unread messages. Return number if its higher than zero.
	 * @param \Leganto\DB\User\Entity $user
	 * @return FALSE|int
	 */
	public function hasNewMessage(\Leganto\DB\User\Entity $user) {
		$count = $this->findAllToUser($user)->where("[read] = %i", 0)->count();
		if ($count == 0) {
			return FALSE;
		} else {
			return $count;
		}
	}

}