<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Templates
 */



/**
 * Defines file-based template methods.
 *
 * @author     David Grudl
 */
interface IFileTemplate extends ITemplate
{

	/**
	 * Sets the path to the template file.
	 * @param  string  template file path
	 * @return void
	 */
	function setFile($file);

	/**
	 * Returns the path to the template file.
	 * @return string  template file path
	 */
	function getFile();

}
