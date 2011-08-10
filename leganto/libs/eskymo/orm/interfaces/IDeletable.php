<?php

/**
 * Interface deletable
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM;

use Leganto\ORM\Workers\IDeleter;

interface IDeletable {

	/**
	 * @return IDeleter
	 */
	function getDeleter();
}
