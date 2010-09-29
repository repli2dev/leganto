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
class FeedItemSelector implements ISelector {

	public function find($id) {
		throw new NotSupportedException("The feed items do not have IDs.");
	}

	/**
	 * Find all feed items
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [tmp_feed]");
	}

	/**
	 * Find all feed items from specific user
	 * @param IEntity $user
	 * @return DibiDataSource
	 */
	public function findAllByUser(IEntity $user) {
		if (empty($user)) {
			throw new NullPointerException("The parameter [user] is empty.");
		}
		if ($user->getId() == NULL) {
			throw new NullPointerException("The user has no ID.");
		}
		return dibi::dataSource("SELECT * FROM [tmp_feed] WHERE [id_user] = %i", $user->getId());
	}

}