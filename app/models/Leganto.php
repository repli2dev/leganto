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
	 * @return IEntitykFactory
	 */
	public static function authors() {
		return SimpleEntityFactory::createEntityFactory("author");
	}

	/**
	 * @return IEntitykFactory
	 */
	public static function books() {
		return SimpleEntityFactory::createEntityFactory("book");
	}

	/**
	 * @return IEntitykFactory
	 */
	public static function opinions() {
		return SimpleEntityFactory::createEntityFactory("opinion");
	}

	/**
	 * @return IEntitykFactory
	 */
	public static function shelves() {
		return SimpleEntityFactory::createEntityFactory("shelf");
	}

	/**
	 * @return IEntitykFactory
	 */
	public static function tags() {
		return SimpleEntityFactory::createEntityFactory("tag");
	}

	/**
	 * @return IEntitykFactory
	 */
	public static function users() {
		return SimpleEntityFactory::createEntityFactory("user");
	}

}
