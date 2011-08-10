<?php

/**
 * Interface deleter
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM\Workers;

interface IDeleter {

	/**
	 * @param integer $id 
	 * @return boolean
	 * @throws InvalidStateException if the record cannot be deleted
	 */
	function delete($id);
}
