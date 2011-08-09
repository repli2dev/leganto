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
namespace Leganto\DB\Discussion;
use Leganto\ORM\Workers\ISelector,
	Leganto\DB\Factory,
	dibi;

class Selector implements ISelector {

	/**
	 * Find one discussion with certain id
	 * @param int $id id of discussion
	 * @return DiscussionEntity
	 */
	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Factory::discussions()->fetchAndCreate($this->findAll()->where("[id_discussion] = %i", $id));
	}

	/**
	 * Find all discussions
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_discussion]");
	}

	/**
	 * Find discussion according to discussed id and type
	 * @param int $discussed
	 * @param int $type
	 * @return DiscussionEntity
	 */
	public function findByDiscussedAndType($discussed, $type) {
		if (empty($discussed)) {
			throw new NullPointerException("discussed");
		}
		if (empty($type)) {
			throw new NullPointerException("type");
		}
		return Factory::discussions()->fetchAndCreate($this->findAll()->where("[id_discussed] = %i", $discussed, " AND [id_discussable] = %i", $type));
	}

}
