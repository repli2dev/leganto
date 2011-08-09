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
namespace Leganto\DB\SupportText;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	\dibi as dibi;

class Selector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all support texts
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [support_text]");
	}

	/**
	 * Find all support texts in category
	 * @param int $category id of category
	 * @return DibiDataSource
	 */
	public function findAllByCategory($category) {
		if (empty($category)) {
			throw new \InvalidArgumentException("Empty category id.");
		}
		return dibi::dataSource("SELECT * FROM [support_text] WHERE id_support_category = %i ORDER BY weight ASC", $category);
	}

	/** @return SupportTextEntity */
	public function find($id) {
		return Factory::supportText()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [support_text] WHERE [id_support_text] = %i", $id)
		);
	}

	/**
	 * Search by given keyword
	 * @param string $keyword what to search for
	 * @return DibiDataSource
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new \InvalidArgumentException("keyword");
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
		return dibi::dataSource("SELECT * FROM [support_text] " . (empty($conditions) ? "" : " WHERE " . $conditions));
	}

}