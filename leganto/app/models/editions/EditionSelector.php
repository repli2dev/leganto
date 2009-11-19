<?php

class EditionSelector implements ISelector
{

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		Leganto::editions()->fetchAndCreate(dibi::dataSource("SELECT * FROM [edition] WHERE [id_edition] = %i", $id));
	}

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [edition]");
	}

}

