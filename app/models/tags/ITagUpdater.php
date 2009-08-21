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
interface ITagUpdater extends IUpdater
{
	/**
	 * It sets a book as tagged by the tag
	 *
	 * @param int $book
	 * @param int $tag
	 */
	public function setTagged($book, $tag);
}
