<?php

/**
 * Achievement selector
 * 
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\DB\Achievement;

use Leganto\ORM\Workers\ISelector,
    Leganto\ORM\Exceptions\NotSupportedException,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {

	public function find($id) {
		throw new NotSupportedException();
	}

	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [tmp_achievement]");
	}

	/**
	 * Return achievements of one user
	 * 
	 * @param \Leganto\DB\User\Entity $user
	 * @return \Leganto\DB\Achievement\Entity
	 */
	public function findByUser(\Leganto\DB\User\Entity $user) {
		return Factory::achievement()->fetchAndCreate($this->connection->dataSource("SELECT * FROM [tmp_achievement] WHERE [id_user] = %i", $user->getId()));
	}

	/**
	 * Return achievements of multiple users
	 * 
	 * @param array $users Array of users (DibiRow or \Leganto\DB\User\Entity)
	 * @param boolean $entities Invoke with true if given $user array contains User entities.
	 * @return array Array of \Leganto\DB\Achievement\Entity
	 */
	public function findByUsers(array $users, $entities = TRUE) {
		if (empty($users)) {
			throw new InvalidArgumentException("Empty users");
		} else {
			if ($entities) {
				$userIds = array();
				foreach ($users AS $user) {
					$userIds[] = $user->getId();
				}
			} else {
				$userIds = $users;
			}
			$source = $this->connection->dataSource("SELECT * FROM [tmp_achievement] WHERE [id_user] IN %l", $userIds);
			$result = array();
			while ($entity = Factory::achievement()->fetchAndCreate($source)) {
				$result[$entity->idUser] = $entity;
			}
			return $result;
		}
	}

}
