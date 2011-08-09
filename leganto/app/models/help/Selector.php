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
namespace Leganto\DB\Help;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	\dibi as dibi;

class Selector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all help (have you known...)
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [help]");
	}

	/** @return HelpEntity */
	public function findRandom(\Leganto\DB\Language\Entity $language, $category = NULL) {
		$source = dibi::dataSource("SELECT * FROM [help] WHERE [id_language] = %i ", $language->getId(), "ORDER BY %sql ", "RAND()");
		if (!empty($category)) {
			$source->where("[category] = %s", $category);
		}
		$source->applyLimit(1);
		return Factory::help()->fetchAndCreate($source);
	}

	/** @return HelpEntity */
	public function find($id) {
		return Factory::help()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [help] WHERE [id_help] = %i", $id)
		);
	}

}