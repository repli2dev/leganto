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
class supportCategorySelector implements ISelector {
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
	public function findAllSortedByWeight() {
		return dibi::dataSource("SELECT * FROM [support_category]")
			->orderBy("weight", "ASC");
	}

	/** @return SuportCategoryEntity */
	public function find($id) {
		return Leganto::supportCategory()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [support_category] WHERE [id_support_category] = %i", $id)
		);
	}

}