<?php

/**
 * Support text inserter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\SupportText;

use Leganto\ORM\Workers\IInserter,
    Leganto\ORM\Workers\SimpleInserter,
    Leganto\ORM\IEntity,
    Leganto\ORM\Workers\AWorker;

class Inserter extends AWorker implements IInserter {

	/**
	 * Insert supportText entity
	 * @param IEntity $entity
	 * @return int supportText id
	 */
	public function insert(IEntity &$entity) {
		return SimpleInserter::createInserter("support_text", $this->connection)->insert($entity);
	}

}