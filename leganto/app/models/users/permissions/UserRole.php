<?php
class UserRole implements IRole
{

    /** @var UserRole */
    private static $loggedRole;

    private $id;

    private $role;

    private final function  __construct($role, $id) {
	$this->role = $role;
	$this->id   = $id;
    }

    public static function getLoggedRole() {
	if (!isset(self::$loggedRole)) {
	    if (Environment::getUser()->isLoggedIn()) {
		$role = ExtraArray::firstValue(Environment::getUser()->getRoles());
		$id   = System::user()->getId();
	    }
	    else {
		$role = UserAuthorizator::GUEST;
		$id   = NULL;
	    }
	    self::$loggedRole = new UserRole($role, $id);
	}
	return self::$loggedRole;
    }

    public function getId() {
	return $this->id;
    }

    public function getRoleId() {
	return $this->role;
    }

}
