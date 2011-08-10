<?php

/**
 * Interface updatable
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM;

interface IUpdateable {

	/**
	 * @return IUpdater
	 */
	function getUpdater();
}
