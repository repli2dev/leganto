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

abstract class AFinder implements IFinder {

	public $xmlURL;

	/**
	 * Set query language
	 * @param string $lang google code of language
	 */
	function  __construct($lang) {
		$this->xmlUrl = strtr($this->xmlUrl, array("<--LANG-->" => $lang));
	}
	
	/**
	 * It returns a content which is placed on the specified address.
	 *
	 * @param string $url
	 * @return string
	 */
	function getURLContent($url) {
		$ch = curl_init($url);
		// The data should be returned (not printed)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		// Redirection hack
		if (preg_match("/Object moved to/", $output) == 0) {
			return $output;
		}
		else {
			preg_match("/http.+\d+\//", $output, $matches);
			// FIXME: fix potencial circular reference
			return $this->getURLContent($matches[0]);
		}
	}

	function setQuery($query) {
		$this->xmlURL = strtr($this->xmlUrl, array("<--QUERY-->" => $query));
	}

}