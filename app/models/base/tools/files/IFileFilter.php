<?php
/**
 * Reader's book
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        http://code.google.com/p/preader/
 * @category    Reader
 * @package     Reader\Base\Files
 * @version     2009-07-04
 */

/*namespace Reader\Base\Files;*/

/**
 * This interface is designed to be implementde by file filters.
 *
 * @author      Jan Papousek
 * @version     2009-07-04
 * @package     Reader\Base\Files
 * @see         FileTypeFilter
 * @see         FileNameFilter
 */
interface IFileFilter
{

	/**
	 * It checks if the file is accepted.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	function accepts(File $file);

}
