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
class TopicSelector implements ISelector
{

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::topics()->fetchAndCreate(dibi::dataSource("SELECT * FROM [view_topic] WHERE [id_topic]", $id));
	}

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_topic]");
	}

}
