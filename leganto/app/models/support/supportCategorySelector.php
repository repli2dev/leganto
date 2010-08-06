<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class supportCategorySelector implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [support_category]");
	}
	
	public function findAllSortedByWeight() {
		return dibi::dataSource("SELECT * FROM [support_category]")
				->orderBy("weight","ASC");
	}

	/** @return BookEntity */
	public function find($id) {
		return Leganto::supportCategory()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [support_category] WHERE [id_support_category] = %i", $id)
			);
	}

}