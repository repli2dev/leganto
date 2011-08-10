<?php

/**
 * Language selector
 * 
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Language;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all languages
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [language]");
	}

	/** @return LanguageEntity */
	public function find($id) {
		return Factory::language()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [language] WHERE [id_language] = %i", $id)
		);
	}

}