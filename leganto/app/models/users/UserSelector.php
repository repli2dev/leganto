<?php

/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class UserSelector implements ISelector {
	/* PUBLIC METHODS */

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_user]");
	}

	public function findAllFollowed(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_followed] WHERE [id_user_following] = %i", $user->getId());
	}

	public function findAllFollowing(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_following] WHERE [id_user_followed] = %i", $user->getId());
	}

	public function isFollowedBy($who, UserEntity $by) {
		if ($by->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		$rows = dibi::dataSource("SELECT * FROM [view_following] WHERE [id_user_followed] = %i AND [id_user] = %i", $who, $by->getId());
		if($rows->count() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function findAllSimilar(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_similar_user] WHERE [id_user_from] = %i", $user->getId());
	}

	public function findByEmail($email) {
		if (empty($email)) {
			throw new NullPointerException("email");
		}
		return Leganto::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_user] WHERE [email] = %s", $email)
		);
	}

	public function findByNick($nick) {
		if (empty($nick)) {
			throw new NullPointerException("nick");
		}
		return Leganto::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_user] WHERE [nick] = %s", $nick)
		);
	}

	/** @return UserEntity */
	public function find($id) {
		return Leganto::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_user] WHERE [id_user] = %i", $id)
		);
	}

	/** @return DibiDataSource */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keyword = "%" . $keyword . "%";

		return dibi::dataSource("SELECT * FROM [view_user] WHERE
				[email] LIKE %s", $keyword, " OR
				[nick] LIKE %s", $keyword, "
		");
	}

}