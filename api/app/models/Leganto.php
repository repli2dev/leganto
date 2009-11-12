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
	 * @return AEntityFactory
	 */
	public static function authors() {
		return SimpleEntityFactory::createEntityFactory("author");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function books() {
		return SimpleEntityFactory::createEntityFactory("book");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function discussions() {
		return SimpleEntityFactory::createEntityFactory("discussion");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function help() {
		return SimpleEntityFactory::createEntityFactory("help");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function opinions() {
		return SimpleEntityFactory::createEntityFactory("opinion");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function posts() {
		return SimpleEntityFactory::createEntityFactory("post");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function shelves() {
		return SimpleEntityFactory::createEntityFactory("shelf");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function tags() {
		return SimpleEntityFactory::createEntityFactory("tag");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function topics() {
		return SimpleEntityFactory::createEntityFactory("topic");
	}

	/**
	 * @return AEntityFactory
	 */
	public static function users() {
		return SimpleEntityFactory::createEntityFactory("user");
	}

}
