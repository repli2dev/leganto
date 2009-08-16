<?php
/**
 * @author Jan Papousek
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
