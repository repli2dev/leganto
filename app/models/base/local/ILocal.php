<?php
/*
 * The web basis called Eskymo
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        [--- ESKYMO REPOSITORY LINK ---]
 * @category    Eskymo
 * @package     Eskymo\Locales
 * @version     2009-07-04
 */

/*namespace Eskymo\Locales;*/

/**
 * This interface is implemented by classes which represents localized texts
 * used in one module.
 *
 * @author      Jan Papousek
 * @version     2009-07-08
 * @package     Eskymo\Locales
 * @see			Local
 */
interface ILocal
{

	/**
	 * It returns a localized string or its default value.
	 *
	 * @param string $key The key name of the text.
	 * @throws NullPointerException if the $key is empty.
	 * @throws DataNotFoundException if the key was not found.
	 */
	function get($key);

}