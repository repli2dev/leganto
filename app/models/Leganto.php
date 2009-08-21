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
class Leganto
{

	/**
	 * @return AuthorFactory
	 */
	public static function authors() {
		return SimpleEntityFactory::createEntityFactory("author");
	}

	/**
	 * @return BookFactory
	 */
	public static function books() {
		return SimpleEntityFactory::createEntityFactory("book");
	}

	/**
	 * @return TagFactory
	 */
	public static function tags() {
		return SimpleEntityFactory::createEntityFactory("tags");
	}

	/**
	 * @return UserFactory
	 */
	public static function users() {
		return SimpleEntityFactory::createEntityFactory("users");
	}

}
