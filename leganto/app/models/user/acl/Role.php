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
namespace Leganto\ACL;
use	\Nette\Security\IRole,
	\Nette\Environment,
	\Leganto\DB\User\Entity;

class Role implements IRole {
	const COMMON = "common";

	const GUEST = "guest";

	const PRIVILEGED = "privileged";

	const ADMIN = "admin";

	/** @var UserRole */
	private static $loggedRole;
	private $id;
	private $role;

	private final function __construct($role, $id) {
		$this->role = $role;
		$this->id = $id;
	}

	/** @return Role */
	public static function getLoggedRole() {
		if (!isset(self::$loggedRole)) {
			if (Environment::getUser()->isLoggedIn()) {
				$role = ExtraArray::firstValue(Environment::getUser()->getRoles());
				$id = System::user()->getId();
			} else {
				$role = self::GUEST;
				$id = NULL;
			}
			self::$loggedRole = new Role($role, $id);
		}
		return self::$loggedRole;
	}

	/** @return string */
	public static function getRoleDescriptor(UserEntity $user) {
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
