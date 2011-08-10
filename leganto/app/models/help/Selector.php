<?php

/**
 * Help selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Help;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {

	/**
	 * Find all help (have you known...)
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [help]");
	}

	/** @return Entity */
	public function findRandom(\Leganto\DB\Language\Entity $language, $category = NULL) {
		$source = $this->connection->dataSource("SELECT * FROM [help] WHERE [id_language] = %i ", $language->getId(), "ORDER BY %sql ", "RAND()");
		if (!empty($category)) {
			$source->where("[category] = %s", $category);
		}
		$source->applyLimit(1);
		return Factory::help()->fetchAndCreate($source);
	}

	/** @return Entity */
	public function find($id) {
		return Factory::help()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [help] WHERE [id_help] = %i", $id)
		);
	}

}