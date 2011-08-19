<?php

/**
 * Feed deleter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Feed;

use Leganto\ORM\Workers\IDeleter,
    Leganto\ORM\IEntity,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Deleter extends AWorker implements IDeleter {

	public function delete($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("Empty id.");
		}
		$this->connection->delete("feed_event")->where("[id_feed_event] = %i",$id)->execute();
	}

}