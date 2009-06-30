<?php
/**
 * This class provides authorization
 *
 * @author Jan Papousek
 */
class UserAuthorizator implements IAuthorizator
{

	private $roles = NULL;

	public function isAllowed($role = IAuthenticator::ALL, $resource = IAuthenticator::ALL, $privilege = IAuthenticator::ALL) {
		if (empty($privilege)) {
			return TRUE;
		}
		if (empty($role)) {
			return FALSE;
		}
		$moduleId = Modules::getInstance()->getId($resource);
		if (empty ($this->roles[$role])) {
			return FALSE;
		}
		foreach ($this->roles[$role] AS $row) {
			if ($row[Permissions::DATA_MODULE] == $moduleId) {
				if ($row[Permissions::DATA_ACTION] == NULL || $row[Permissions::DATA_ACTION] == $privilege) {
					Debug::dump($row);
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	private function getRoles() {
		if ($this->roles === NULL) {
			$roles = new Role();
			$this->roles = $roles->getWithPrivileges()->fetchAssoc(Role::DATA_NAME);
		}
		return $this->roles;
	}	
}