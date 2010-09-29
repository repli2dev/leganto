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
class ConnectionSelector implements ISelector {
	/* PUBLIC METHODS */

	/** @return DataSource */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [connection]");
	}

	/** @return DataSource */
	public function findAllFromUser($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		return $this->findAll()->where('id_user = %i', $user);
	}

	/** @return UserConnectionsEntity */
	public function find($id) {
		return Leganto::connections()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [connection] WHERE [id_connection] = %i", $id)
		);
	}

	/** @return boolean */
	public function exists($user, $type) {
		$data = dibi::dataSource("SELECT * FROM [connection] WHERE [id_user] = %i", $user, "AND [type] = %s", $type);
		if ($data->count() == 1) {
			return true;
		} else {
			return false;
		}
	}

	/** @return int */
	public function getToken($user, $type) {
		$data = dibi::query("SELECT * FROM [connection] WHERE [id_user] = %i", $user, "AND [type] = %s", $type)->fetch();
		return $data['token'];
	}

}