<?php

/**
 * Lazy factory for delegating work to someone else
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class Leganto {

	/**
	 * @return IEntityFactory
	 */
	public static function achievement () {
		return SimpleEntityFactory::createEntityFactory("achievement");
	}

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
	public static function captcha() {
		return SimpleEntityFactory::createEntityFactory("captcha");
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
	public static function feed() {
		return SimpleEntityFactory::createEntityFactory("feedItem");
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
	public static function languages() {
		return SimpleEntityFactory::createEntityFactory("language");
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

	/**
	 * @return IEntityFactory
	 */
	public static function supportCategory() {
		return SimpleEntityFactory::createEntityFactory("supportCategory");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function supportText() {
		return SimpleEntityFactory::createEntityFactory("supportText");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function connections() {
		return SimpleEntityFactory::createEntityFactory("connection");
	}

	/**
	 * @return IEntityFactory
	 */
	public static function messages() {
		return SimpleEntityFactory::createEntityFactory("message");
	}

}
