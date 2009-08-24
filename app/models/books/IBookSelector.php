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
 * Interface which extends common ISelector
 *
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
interface IBookSelector extends ISelector
{
	
	public function findAllByAuthor(IEntity $author);

	public function findOthers(BookEntity $book);

	public function search($query);

}
