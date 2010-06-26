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
class DiscussionSelector implements ISelector
{

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::discussions()->fetchAndCreate($this->findAll()->where("[id_discussion] = %i", $id));
	}

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_discussion]");
	}

        public function findByDiscussedAndType($discussed, $type) {
		if (empty($discussed)) {
			throw new NullPointerException("discussed");
		}
		if (empty($type)) {
			throw new NullPointerException("type");
		}
                return Leganto::discussions()->fetchAndCreate($this->findAll()->where("[id_discussed] = %i", $discussed, " AND [id_discussable] = %i", $type));
        }

}
