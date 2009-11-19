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
	 * @return IEntityFactory
	 */
	public static function authors() {
		return SimpleEntityFactory::createEntityFactory("author");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function books() {
		return SimpleEntityFactory::createEntityFactory("book");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function discussions() {
		return SimpleEntityFactory::createEntityFactory("discussion");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function editions() {
		return SimpleEntityFactory::createEntityFactory("edition");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function help() {
		return SimpleEntityFactory::createEntityFactory("help");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function opinions() {
		return SimpleEntityFactory::createEntityFactory("opinion");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function posts() {
		return SimpleEntityFactory::createEntityFactory("post");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function shelves() {
		return SimpleEntityFactory::createEntityFactory("shelf");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function tags() {
		return SimpleEntityFactory::createEntityFactory("tag");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function topics() {
		return SimpleEntityFactory::createEntityFactory("topic");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function users() {
		return SimpleEntityFactory::createEntityFactory("user");
	}

}
