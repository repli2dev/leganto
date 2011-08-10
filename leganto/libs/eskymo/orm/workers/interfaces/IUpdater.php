<?php

/**
 * Interface updater
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://code.google.com/p/eskymofw/
 */

namespace Leganto\ORM\Workers;

use Leganto\ORM\IEntity;

interface IUpdater {

	/**
	 * @param array
	 * @return int
	 * @throws InvalidStateException if the $entity has no ID
	 */
	function update(IEntity $entity);
}
