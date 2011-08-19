<?php

/**
 * Connection selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Connection;

use Leganto\ORM\Workers\AWorker,
    Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {
	/* PUBLIC METHODS */

	/** @return DibiDataSource */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [connection]");
	}

	/**
	 * Find all connection from given user
	 * @param int $user ID of user
	 * @return DibiDataSource
	 */
	public function findAllFromUser($user) {
		if (empty($user)) {
			throw new InvalidArgumentException("Empty user.");
		}
		return $this->findAll()->where('id_user = %i', $user);
	}

	/** @return Entity */
	public function find($id) {
		return Factory::connection()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [connection] WHERE [id_connection] = %i", $id)
		);
	}

	/**
	 * Check if given user has connection of given type
	 * @param int $user ID of user
	 * @param string $type Type of connection (@see Entity)
	 * @return boolean
	 */
	public function exists($user, $type) {
		$data = $this->connection->dataSource("SELECT * FROM [connection] WHERE [id_user] = %i", $user, "AND [type] = %s", $type);
		if ($data->count() == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if given token (of given type) is already present in database
	 * @param string $type Type of connection (@see Entity)
	 * {param string $token Token of user (user id from the other side)
	 * @return boolean
	 */
	public function tokenExists($type, $token) {
		$data = $this->connection->dataSource("SELECT * FROM [connection] WHERE [token] = %i", $token, "AND [type] = %s", $type);
		if ($data->count() == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Return token of user of given connection type
	 * @param int $user ID of user
	 * @param string $type Type of connection (@see Entity)
	 * @return int
	 */
	public function getToken($user, $type) {
		$data = $this->connection->query("SELECT * FROM [connection] WHERE [id_user] = %i", $user, "AND [type] = %s", $type)->fetch();
		return $data['token'];
	}
	
	/**
	 * Return token of user of given connection type
	 * @param int $user ID of user
	 * @param string $type Type of connection (@see Entity)
	 * @return int
	 */
	public function getTokenAndSecret($user, $type) {
		$data = $this->connection->query("SELECT * FROM [connection] WHERE [id_user] = %i", $user, "AND [type] = %s", $type)->fetch();
		return array("token" => $data['token'], "secret" => $data["secret"]);
	}

}