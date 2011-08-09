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
namespace Leganto\DB\SupportCategory;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	\dibi as dibi;

class Selector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all categories in help
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [support_category]");
	}

	/**
	 * Find all categories in help ordered by weight
	 * @return DibiDataSource
	 */
	public function findAllSortedByWeight($language = NULL) {
		$data = dibi::dataSource("SELECT * FROM [support_category]")
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
				dibi::dataSource("SELECT * FROM [support_category] WHERE [id_support_category] = %i", $id)
		);
	}

}