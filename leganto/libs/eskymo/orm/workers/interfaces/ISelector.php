<?php

/**
 * Interface selector
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM\Workers;

interface ISelector {

	/**
	 * @param integer $id 
	 * @return IEntity
	 */
	function find($id);

	/**
	 * @return DibiDataSource
	 */
	function findAll();
}
