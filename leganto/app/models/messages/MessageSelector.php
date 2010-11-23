<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

class MessageSelector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Return all entries
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [message]");
	}

	/**
	 * Return all messages send or receive by user
	 * @param UserEntity $user
	 * @return DibiDataSource
	 */
	public function findAllWithUser(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [message] WHERE [id_user_from] = %i OR [id_user_to] = %i ORDER BY inserted DESC", $user->getId(),$user->getId());
	}

	public function find($id) {
		throw new NotImplementedException();
	}

}