<?php

/**
 * Interface inserter
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM\Workers;

use Leganto\ORM\IEntity;

interface IInserter {

	/**
	 * @param array
	 * @return int
	 * @throws InvalidStateException if the $entity has been alrady inserted
	 */
	function insert(IEntity &$entity);
}
