<?php

/**
 * This interface is designed to be implementde by file filters.
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @see         FileTypeFilter
 * @see         FileNameFilter
 */

namespace Leganto\IO;

use Nette\Object;

interface IFileFilter {

	/**
	 * It checks if the file is accepted.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws InvalidArgumentException if the $file is empty.
	 */
	function accepts(File $file);
}
