<?php
/**
 * @author Jan Papousek
 */
class SimpleAuthorizator implements IAuthorizator
{

	const ADMIN = "admin";

	const COMMON = "common";

	const GUEST = "guest";

	const MODERATOR = "moderator";

	/**
	 * The permission descriptor.
	 *
	 * @var Permission
	 */
	private $permission = NULL;

	/**
	 * Action when the user edit the entity which is owned by him/her.
	 */
	const ACTION_EDIT = "edit";

	/**
	 * Action when the user is allowed to edit all entities.
	 */
	const ACTION_EDIT_ALL = "edit_all";

	/**
	 * Action when the user inserts data.
	 */
	const ACTION_INSERT = "insert";

	/**
	 * Action when the user reads data which is owned by him/her.
	 */
	const ACTION_READ = "read";

	/**
	 * Action when the user is allowed to read all info about entities.
	 */
	const ACTION_READ_ALL = "read_all";

	public function isAllowed($role = IAuthenticator::ALL, $resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL) {
		// If there is no specified privilege, the user is allowed.
		if (empty($privilege)) {
			return TRUE;
		}
		// The user without role can't be allowed.
		if (empty($role)) {
			return false;
		}
		return $this->getPermission()->isAllowed($role, $resource, $privilege);
	}

	/**
	 * It returns a permission descriptor.
	 *
	 * @return Permission
	 */
	private function getPermission() {
		if (empty($this->permission)) {
			$this->permission = new Permission();
			// Used roles
			$this->permission->addRole(self::GUEST);
			$this->permission->addRole(self::COMMON, self::GUEST);
			$this->permission->addRole(self::MODERATOR, self::COMMON);
			$this->permission->addRole(self::ADMIN, self::MODERATOR);

			$this->permission->allow(self::COMMON, IAuthorizator::ALL, self::ACTION_READ);
			$this->permission->allow(self::COMMON, IAuthorizator::ALL, self::ACTION_INSERT);
			$this->permission->allow(self::COMMON, IAuthorizator::ALL, self::ACTION_EDIT);

			$this->permission->allow(self::MODERATOR, IAuthorizator::ALL, self::ACTION_EDIT_ALL);
			$this->permission->allow(self::MODERATOR, IAuthorizator::ALL, self::ACTION_READ_ALL);

		}
		return $this->permission;
	}

}
