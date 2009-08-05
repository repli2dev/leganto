<?php
/**
 * @author Jan Papousek
 */
class RoleTest extends TableModelTest
{

	protected function createInstance() {
		return new Role();
	}

	protected function getUpdate() {
		return array(Role::DATA_NAME => "aaaa");
	}

	protected function getEntity() {
		return array(
			Role::DATA_NAME => "bbb"
		);
	}

}
