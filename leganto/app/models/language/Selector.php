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
namespace Leganto\DB\Language;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	\dibi as dibi;

class Selector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all languages
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [language]");
	}

	/** @return LanguageEntity */
	public function find($id) {
		return Factory::languages()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [language] WHERE [id_language] = %i", $id)
		);
	}

}