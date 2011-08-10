<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\Storage;

use Leganto\IO\File;

interface IStorage {

	/**
	 * Get file of given entity
	 * @param \Leganto\ORM\IEntity $entity
	 * @return File
	 */
	function getFile(\Leganto\ORM\IEntity $entity);

	/**
	 * Store file for given entity
	 * @param \Leganto\ORM\IEntity $entity
	 * @param File $file
	 * @return File
	 */
	function store(\Leganto\ORM\IEntity $entity, File $file);
}
