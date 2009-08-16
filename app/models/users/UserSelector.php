<?php
/**
 * @author Jan Drabek
 */
class UserSelector extends Worker implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_user]");
	}
	
	/** @return UserEntity */
	public function findOne($id) {
		$row = dibi::dataSource("SELECT * FROM [view_user] WHERE [id_user] = %i", $id)->fetch();
		$entity = new UserEntity;
		return empty($row) ? NULL : $entity->loadDataFromRow($row);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("user");
	}
}