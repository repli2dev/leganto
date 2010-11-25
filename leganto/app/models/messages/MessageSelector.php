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
		return dibi::dataSource("SELECT * FROM [view_message]");
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
		return dibi::dataSource("SELECT * FROM [view_message] WHERE ([id_user_from] = %i AND [deleted_by_owner] = 0) OR ([id_user_to] = %i AND [deleted_by_recipient] = 0) ORDER BY inserted DESC", $user->getId(),$user->getId());
	}

	public function findAllToUser(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_message] WHERE [id_user_to] = %i ORDER BY inserted DESC", $user->getId());
	}

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::messages()->fetchAndCreate(dibi::DataSource("SELECT * FROM [view_message] WHERE [id_message] = %i", $id));
	}

	/**
	 * Return false if user has some unread messages. Return number if its higher than zero.
	 * @param UserEntity $user
	 */
	public function hasNewMessage(UserEntity $user) {
		$count = $this->findAllToUser($user)->where("[read] = %i",0)->count();
		if($count == 0) {
			return FALSE;
		} else {
			return $count;
		}
	}

}