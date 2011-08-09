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
namespace Leganto\DB\Domain;
use Leganto\ORM\Workers\ISelector,
	\dibi as dibi;

class Selector implements ISelector {

	public function find($id) {
		if (empty($id)) {
			throw new \InvalidArgumentException("id");
		}
		return SimpleEntityFactory::createEntityFactory("domain")
			->fetchAndCreate(
				$this->findAll()->where("[id_domain] = %i", $id)
		);
	}

	/** @return DibiDataSource */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_domain]");
	}

}
