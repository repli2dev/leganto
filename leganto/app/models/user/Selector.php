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
namespace Leganto\DB\User;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	\dibi as dibi;

class Selector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Return all entries
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_user]");
	}

	/**
	 * Return all users followed by given user
	 * @param UserEntity $user
	 * @return DibiDataSource
	 */
	public function findAllFollowed(Entity $user) {
		if ($user->getId() == NULL) {
			throw new \InvalidArgumentException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_followed] WHERE [id_user_following] = %i", $user->getId());
	}

	/**
	 * Return all users which follow given user
	 * @param UserEntity $user
	 * @return DibiDataSource
	 */
	public function findAllFollowing(Entity $user) {
		if ($user->getId() == NULL) {
			throw new \InvalidArgumentException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_following] WHERE [id_user_followed] = %i", $user->getId());
	}

	/**
	 * Check if user (who) is followed by another user (by)
	 * @param int $who id of user
	 * @param UserEntity $by user entity
	 * @return Boolean
	 */
	public function isFollowedBy($who, Entity $by) {
		if ($by->getId() == NULL) {
			throw new \InvalidArgumentException("user:id");
		}
		$rows = dibi::dataSource("SELECT * FROM [view_following] WHERE [id_user_followed] = %i AND [id_user] = %i", $who, $by->getId());
		if($rows->count() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Find all similar users
	 * @param UserEntity $user
	 * @return DibiDataSource
	 */
	public function findAllSimilar(Entity $user) {
		if ($user->getId() == NULL) {
			throw new \InvalidArgumentException("user:id");
		}
		return dibi::dataSource("SELECT * FROM [view_similar_user] WHERE [id_user_from] = %i", $user->getId());
	}

	/**
	 * Find user by email and return whole user
	 * @param string $email
	 * @return UserEntity
	 */
	public function findByEmail($email) {
		if (empty($email)) {
			throw new \InvalidArgumentException("email");
		}
		return Factory::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_user] WHERE [email] = %s", $email)
		);
	}

	/**
	 * Find user by nickname and return whole user
	 * @param string $nick
	 * @return UserEntity
	 */
	public function findByNick($nick) {
		if (empty($nick)) {
			throw new \InvalidArgumentException("nick");
		}
		return Factory::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_user] WHERE [nick] = %s", $nick)
		);
	}

	/** @return UserEntity */
	public function find($id) {
		return Factory::users()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_user] WHERE [id_user] = %i", $id)
		);
	}

	/** @return DibiDataSource */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new \InvalidArgumentException("keyword");
		}
		$keyword = "%" . $keyword . "%";

		return dibi::dataSource("SELECT * FROM [view_user] WHERE
				[about] LIKE %s", $keyword, " OR
				[nick] LIKE %s", $keyword, "
		");
	}

}