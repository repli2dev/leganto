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
class TopicSelector implements ISelector {

	/**
	 * Find one certain topic by id
	 * @param int $id id of topic
	 * @return TopicEntity
	 */
	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::topics()->fetchAndCreate(dibi::dataSource("SELECT * FROM [view_topic] WHERE [id_topic] = %i", $id));
	}

	/**
	 * Find all topics
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_topic]");
	}

}
