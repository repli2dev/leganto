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
 
interface ISocialNetwork {

	/**
	 * Should do login aganist social network API and return true/false. Temporary login data should be stored in session.
	 * @return boolean
	 */
	function authentication();

	/**
	 * Do any nescessary steps to login user in
	 */
	function doLogin();

	/**
	 * Decide (according to global config) if this social network is enabled.
	 * @return boolean
	 */
	function isEnabled();

}
