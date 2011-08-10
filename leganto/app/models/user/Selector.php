<?php

/**
 * User selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\User;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Return all entries
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_user]");
	}

	/**
	 * Return all users followed by given user
	 * @param Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user id is not set
	 */
	public function findAllFollowed(Entity $user) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("User id is not set.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_followed] WHERE [id_user_following] = %i", $user->getId());
	}

	/**
	 * Return all users which follow given user
	 * @param Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user id is not set
	 */
	public function findAllFollowing(Entity $user) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("User id is not set.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_following] WHERE [id_user_followed] = %i", $user->getId());
	}

	/**
	 * Check if user (who) is followed by another user (by)
	 * @param int $who id of user
	 * @param Entity $by user entity
	 * @return boolean
	 * @throws InvalidArgumentException if user id is not set
	 */
	public function isFollowedBy($who, Entity $by) {
		if ($by->getId() == NULL) {
			throw new InvalidArgumentException("User id is not set.");
		}
		$rows = $this->connection->dataSource("SELECT * FROM [view_following] WHERE [id_user_followed] = %i AND [id_user] = %i", $who, $by->getId());
		if ($rows->count() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Find all similar users
	 * @param Entity $user
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if user id is not set
	 */
	public function findAllSimilar(Entity $user) {
		if ($user->getId() == NULL) {
			throw new InvalidArgumentException("User id is not set.");
		}
		return $this->connection->dataSource("SELECT * FROM [view_similar_user] WHERE [id_user_from] = %i", $user->getId());
	}

	/**
	 * Find user by email and return whole user
	 * @param string $email
	 * @return Entity
	 * @throws InvalidArgumentException if email is empty
	 */
	public function findByEmail($email) {
		if (empty($email)) {
			throw new InvalidArgumentException("Empty email.");
		}
		return Factory::user()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_user] WHERE [email] = %s", $email)
		);
	}

	/**
	 * Find user by nickname and return whole user
	 * @param string $nick
	 * @return Entity
	 * @throws InvalidArgumentException if nick is empty
	 */
	public function findByNick($nick) {
		if (empty($nick)) {
			throw new InvalidArgumentException("Empty nick.");
		}
		return Factory::user()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_user] WHERE [nick] = %s", $nick)
		);
	}

	/** @return UserEntity */
	public function find($id) {
		return Factory::user()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [view_user] WHERE [id_user] = %i", $id)
		);
	}

	/**
	 * Search given keywoard 
	 * @param string $keyword
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if keyword is empty
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("keyword");
		}
		$keyword = "%" . $keyword . "%";

		return $this->connection->dataSource("SELECT * FROM [view_user] WHERE
				[about] LIKE %s", $keyword, " OR
				[nick] LIKE %s", $keyword, "
		");
	}

}