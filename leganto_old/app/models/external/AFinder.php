<?php

/**
 * Abstract layer for finders (for something in internet)
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
abstract class AFinder implements IFinder {
	const REDIRECTION_LIMIT = 5;

	private $params = array();

	/* PROTECTED METHODS */

	/**
	 * It returns a parsed URL
	 *
	 * @return string
	 */
	protected final function getParsedUrl() {
		return strtr($this->getUrl(), $this->getUrlParams());
	}

	/**
	 * It returns a content which is placed on the specified address.
	 *
	 * @param string $url
	 * @return string
	 */
	protected function getUrlContent($url, $counter = 0) {
		if ($counter >= self::REDIRECTION_LIMIT) {
			throw new IOException("The infinite loop.");
		}
		$ch = curl_init($url);
		// The data should be returned (not printed)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		// Redirection hack
		if (preg_match("/Object moved to/", $output) == 0) {
			return $output;
		} else {
			preg_match("/http.+\d+\//", $output, $matches);
			return $this->getURLContent($matches[0], $counter + 1);
		}
	}

	/**
	 * It returns not parsed URL
	 *
	 * @return string
	 */
	protected abstract function getUrl();

	/**
	 * It returns array with URL params
	 *
	 * @return array
	 */
	protected final function getUrlParams() {
		return $this->params;
	}

	/**
	 * I sets an URL param
	 *
	 * @param string $key
	 * @param string $value
	 * @throws NullPointerException if the $key or $value is empty
	 */
	protected final function setUrlParam($key, $value) {
		if (empty($key)) {
			throw new NullPointerException("key");
		}
		if (empty($value)) {
			throw new NullPointerException("value");
		}
		$this->params["<--" . String::upper($key) . "-->"] = $value;
	}

}