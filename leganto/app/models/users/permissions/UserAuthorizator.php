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
class UserAuthorizator implements IAuthorizator {

	/**
	 * The permission descriptor.
	 *
	 * @var Permission
	 */
	private $permission = NULL;

	public function isAllowed($role = IAuthenticator::ALL, $resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL) {
		// If there is no specified privilege, the user is allowed.
		if (empty($privilege)) {
			return TRUE;
		}
		// The user without role can't be allowed.
		if (empty($role)) {
			return false;
		}
		return $this->getPermission()->isAllowed(Role::getLoggedRole($role), $resource, $privilege);
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
			$this->permission->addRole(Role::GUEST);
			$this->permission->addRole(Role::COMMON, Role::GUEST);
			$this->permission->addRole(Role::PRIVILEGED, Role::COMMON);

			$this->permission->addResource(Resource::AUTHOR);
			$this->permission->addResource(Resource::BOOK);
			$this->permission->addResource(Resource::EDITION);
			$this->permission->addResource(Resource::OPINION);
			$this->permission->addResource(Resource::POST);
			$this->permission->addResource(Resource::SHELF);
			$this->permission->addResource(Resource::TAG);
			$this->permission->addResource(Resource::TOPIC);
			$this->permission->addResource(Resource::USER);

			$this->permission->allow(Role::GUEST, IAuthorizator::ALL, Action::READ);

			$ownership = new OwnerAssertion();

			$this->permission->allow(Role::COMMON, IAuthorizator::ALL, Action::INSERT);
			$this->permission->allow(Role::COMMON, Resource::EDITION, Action::EDIT);
			$this->permission->allow(Role::COMMON, Resource::OPINION, Action::EDIT, $ownership);
			$this->permission->allow(Role::COMMON, Resource::POST, Action::EDIT, $ownership);
			$this->permission->allow(Role::COMMON, Resource::SHELF, Action::EDIT, $ownership);
			$this->permission->allow(Role::COMMON, Resource::USER, Action::EDIT, $ownership);

			$this->permission->allow(Role::PRIVILEGED, Resource::AUTHOR, Action::EDIT);
			$this->permission->allow(Role::PRIVILEGED, Resource::BOOK, Action::EDIT);
			$this->permission->allow(Role::PRIVILEGED, Resource::TAG, Action::EDIT);
		}
		return $this->permission;
	}

}