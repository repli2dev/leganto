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
 * Interface for basic delete operations
 *
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
interface IDeleter
{
	
    /**
     * @param integer $id 
	 * @return boolean
	 * @throws InvalidStateException if the record cannot be deleted
	 */
	function delete($id);

}
