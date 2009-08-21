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

	private static $factories = array();

	/**
	 * @return AuthorFactory
	 */
	public static function authors() {
		if (empty(self::$factories["author"])) {
			self::$factories["authors"] = new AuthorFactory();
		}
		return self::$factories["authors"];
	}

	/**
	 * @return BookFactory
	 */
	public static function books() {
		if (empty(self::$factories["books"])) {
			self::$factories["books"] = new BookFactory();
		}
		return self::$factories["books"];
	}

	/**
	 * @return TagFactory
	 */
	public static function tags() {
		if (empty(self::$factories["tags"])) {
			self::$factories["tags"] = new TagFactory();
		}
		return self::$factories["tags"];
	}

	/**
	 * @return UserFactory
	 */
	public static function users() {
		if (empty(self::$factories["users"])) {
			self::$factories["users"] = new UserFactory();
		}
		return self::$factories["users"];
	}

}
