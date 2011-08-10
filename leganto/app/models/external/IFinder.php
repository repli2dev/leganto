<?php

/**
 * Interface for external finder
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\External;

interface IFinder {

	/**
	 * Return array of values from input XML
	 * @param $param
	 */
	function get($param);
}
