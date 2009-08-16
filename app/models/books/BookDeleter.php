<?php
/**
 * @author Jan Drabek
 */
class BookDeleter extends Worker implements IDeleter
{

	/* PUBLIC METHODS */
	
	public function delete($id) {
		
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		throw new NotSupportedException();
	}
}