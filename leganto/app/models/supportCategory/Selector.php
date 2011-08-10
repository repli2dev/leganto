<?php

/**
 * Support category selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\SupportCategory;

use Leganto\ORM\Workers\ISelector,
    Leganto\ORM\Workers\AWorker,
    Leganto\DB\Factory;

class Selector extends AWorker implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all categories in help
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [support_category]");
	}

	/**
	 * Find all categories in help ordered by weight
	 * @return DibiDataSource
	 */
	public function findAllSortedByWeight($language = NULL) {
		$data = $this->connection->dataSource("SELECT * FROM [support_category]")
			->orderBy("weight", "ASC");
		if (!empty($language)) {
			$data->where("id_language = %i", $language);
		}
		return $data;
	}

	/** @return SuportCategoryEntity */
	public function find($id) {
		return Factory::supportCategory()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [support_category] WHERE [id_support_category] = %i", $id)
		);
	}

}