<?php

/**
 * Domain selector
 * 
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Domain;

use Leganto\ORM\Workers\ISelector,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Selector extends AWorker implements ISelector {

	public function find($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("id");
		}
		return SimpleEntityFactory::createEntityFactory("domain")
				->fetchAndCreate(
					$this->findAll()->where("[id_domain] = %i", $id)
		);
	}

	/** @return DibiDataSource */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [view_domain]");
	}

}
