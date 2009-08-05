<?php
/**
 * @author Jan Papousek
 */
class PermissionsTest extends TableModelTest
{

	public function  __construct() {
		$role = new Role();
		$role->insert(array(Role::DATA_NAME => "sdjsdbv"));
		$role->insert(array(Role::DATA_NAME => "sdv"));
	}

	public function  __destruct() {
		dibi::query("TRUNCATE TABLE %n", Role::getTable());
	}

	protected function createInstance() {
		return new Permissions();
	}

	protected function getUpdate() {
		return array(Permissions::DATA_ROLE => 2);
	}

	protected function getEntity() {
		return array(
			Permissions::DATA_ROLE => 1,
			Permissions::DATA_MODULE => 1
		);
	}

}
