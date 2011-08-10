<?php

/**
 * Support text selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\SupportText;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    InvalidArgumentException,
    Leganto\ORM\Workers\AWorker;

class Selector extends AWorker implements ISelector {

	/**
	 * Find all support texts
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [support_text]");
	}

	/**
	 * Find all support texts in category
	 * @param int $category id of category
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if category is empty
	 */
	public function findAllByCategory($category) {
		if (empty($category)) {
			throw new InvalidArgumentException("Empty category.");
		}
		return $this->connection->dataSource("SELECT * FROM [support_text] WHERE id_support_category = %i ORDER BY weight ASC", $category);
	}

	/** @return Entity */
	public function find($id) {
		return Factory::supportText()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [support_text] WHERE [id_support_text] = %i", $id)
		);
	}

	/**
	 * Search by given keyword
	 * @param string $keyword what to search for
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if keyword is empty
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("Empty keyword.");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		foreach ($keywords AS $word) {
			if (!empty($conditions)) {
				$conditions .= " AND ";
			}
			$word = "%" . mysql_escape_string($word) . "%";
			$conditions .= "
			([name] LIKE '$word' OR
			[text] LIKE '$word')";
		}
		return $this->connection->dataSource("SELECT * FROM [support_text] " . (empty($conditions) ? "" : " WHERE " . $conditions));
	}

}