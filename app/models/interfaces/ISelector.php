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
 * Interface for basic select operations, all other select operations should
 * be defined in new interface which have to inherit this one!
 *
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
interface ISelector 
{
	
    /**
     * @param integer $id 
	 * @return IEntity
	 */
	function findOne($id);
	
	/**
	 * @return DataSource
	 */
	function findAll();

}
