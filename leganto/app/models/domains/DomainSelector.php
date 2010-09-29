<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class DomainSelector implements ISelector {

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return SimpleEntityFactory::createEntityFactory("domain")
			->fetchAndCreate(
				$this->findAll()->where("[id_domain] = %i", $id)
		);
	}

	/** @return DibiDataSource */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_domain]");
	}

}
