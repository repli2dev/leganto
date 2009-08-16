<?php
/**
 * @author Jan Drabek
 */
class UserDeleter extends Worker implements IDeleter
{

	/* PUBLIC METHODS */
	
	public function delete($id) {
		
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("user");
	}
}