<?php

/**
 * Lazy factory for delegating work to someone else
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB;

use Leganto\ORM\SimpleEntityFactory,
    DibiConnection;

final class Factory {

	/** @var DibiConnection */
	private static $connection;

	/**
	 * Inject DIBI database connection
	 * 
	 * @param DibiConnection $connection Inject DIBI database connection
	 */
	public static function setConnection(DibiConnection $connection) {
		self::$connection = $connection;
	}

	/**
	 * Automatic creation of SimpleEntityFactory
	 * @param string $name
	 * @param array $args
	 * @return \Leganto\ORM\IEntityFactory
	 */
	public static function __callStatic($name, $args) {
		return SimpleEntityFactory::createEntityFactory($name, self::$connection);
	}

}