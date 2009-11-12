<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class UserSelector implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [user]");
	}

	public function findAllSimilar(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_similar_user] WHERE [id_user_from] = %i", $user->getId());
	}

	/** @return UserEntity */
	public function find($id) {
		return Leganto::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [user] WHERE [id_user] = %i", $id)
			);
	}
	/** @return DibiDataSource */
	public function search($keyword){
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keyword = "%".$keyword."%";

		return dibi::dataSource("SELECT * FROM [user] WHERE
				[email] LIKE %s", $keyword," OR
				[nick] LIKE %s", $keyword,"
		");
	}
}