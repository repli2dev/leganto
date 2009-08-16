<?php
/**
 * @author Jan Drabek
 */
class TagDeleter extends Worker implements IDeleter
{

	/* PUBLIC METHODS */
	
	public function delete($id) {
		
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("tag");
	}
}