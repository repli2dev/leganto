<?php
/**
 * @author Jan Papousek
 */
class UserAuthorizator implements IAuthorizator {

    const COMMON	= "common";

    const GUEST		= "guest";

    const LIMIT_TO_BE_PRIVILEGED = 20;

    const PRIVILEGED	= "privileged";

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
     * Action when the user inserts data.
     */
    const ACTION_INSERT = "insert";

    /**
     * Action when the user reads data which is owned by him/her.
     */
    const ACTION_READ = "read";

    public static function getRole(UserEntity $user) {
	if ($user->role == UserEntity::COMMON) {
	    $opinions = Leganto::opinions()->getSelector()->findAll()->where("[id_user] = %i", $user->getId());
	    if ($opinions->count() >= self::LIMIT_TO_BE_PRIVILEGED) {
		$role = self::PRIVILEGED;
	    }
	    else {
		$role = self::COMMON;
	    }
	}
	else {
	    $role = self::PRIVILEGED;
	}
	return $role;
    }

    public function isAllowed($role = IAuthenticator::ALL, $resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL) {
	// If there is no specified privilege, the user is allowed.
	if (empty($privilege)) {
	    return TRUE;
	}
	// The user without role can't be allowed.
	if (empty($role)) {
	    return false;
	}
	return $this->getPermission()->isAllowed(UserRole::getLoggedRole($role), $resource, $privilege);
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
	    $this->permission->addRole(self::PRIVILEGED, self::COMMON);

	    $this->permission->addResource(Resource::AUTHOR);
	    $this->permission->addResource(Resource::BOOK);
	    $this->permission->addResource(Resource::OPINION);
	    $this->permission->addResource(Resource::POST);
	    $this->permission->addResource(Resource::TAG);
	    $this->permission->addResource(Resource::USER);

	    $this->permission->allow(self::GUEST, IAuthorizator::ALL, self::ACTION_READ);

	    $ownership = new OwnerAssertion();

	    $this->permission->allow(self::COMMON, IAuthorizator::ALL, self::ACTION_INSERT);
	    $this->permission->allow(self::COMMON, Resource::OPINION, self::ACTION_EDIT, $ownership);
	    $this->permission->allow(self::COMMON, Resource::POST, self::ACTION_EDIT, $ownership);
	    $this->permission->allow(self::COMMON, Resource::USER, self::ACTION_EDIT, $ownership);

	    $this->permission->allow(self::PRIVILEGED, Resource::AUTHOR, self::ACTION_EDIT);
	    $this->permission->allow(self::PRIVILEGED, Resource::BOOK, self::ACTION_EDIT);
	    $this->permission->allow(self::PRIVILEGED, Resource::TAG, self::ACTION_EDIT);
	}
	return $this->permission;
    }

}