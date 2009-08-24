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
interface IEntity
{

	/**
	 * It returns the entity ID
	 * @return int
	 */
	function getId();

	/**
	 * It checks if the entity can be inserted.
	 * @return bool
	 */
	function isReadyToInsert();

	/**
	 * It checks if the entity can be updated
	 * @return bool
	 */
	function isReadyToUpdate();

	/**
	 * It loads the data from DibiRow
	 *
	 * WARNING: It deletes old data!
	 * @param array Source data
	 * @return IEntity This method is fluent.
	 */
	function loadDataFromArray(array $resource);

}

