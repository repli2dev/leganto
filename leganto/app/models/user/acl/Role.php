<?php

/**
 * Role of user
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\ACL;

use Nette\Security\IRole,
    Nette\Environment,
    Leganto\DB\User\Entity,
    Leganto\Tools\ExtraArray;

class Role implements IRole {
	const COMMON = "common";

	const GUEST = "guest";

	const PRIVILEGED = "privileged";

	const ADMIN = "admin";

	private static $user;
	private $id;
	private $role;

	/**
	 * Set current user
	 * @param Object $user 
	 */
	public static function setUser($user) {
		self::$user = $user;
	}

	private final function __construct($role, $id) {
		$this->role = $role;
		$this->id = $id;
	}

	/** @return Role */
	public static function get($role, $id = NULL) {
		$loggedRole = self::$user->getRoles();
		if (!isSet($loggedRole) || count($loggedRole) == 0) {
			$role = self::GUEST;
			$id = NULl;
		} else {
			$role = ExtraArray::firstValue($loggedRole);
			$id = self::$user->getId();
		}
		return new Role($role, $id);
	}

	/** @return string */
	public static function getRoleDescriptor(Entity $user) {
		if ($user->role == Entity::ADMIN) {
			$role = self::ADMIN;
		} else
		if ($user->role == Entity::COMMON) {
			$role = self::COMMON;
		} else {
			$role = self::PRIVILEGED;
		}
		return $role;
	}

	public function getId() {
		return $this->id;
	}

	public function getRoleId() {
		return $this->role;
	}

}
