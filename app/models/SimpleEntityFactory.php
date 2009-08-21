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
class SimpleEntityFactory extends AEntityFactory
{

	/**
	 * Entity name
	 *
	 * @var string
	 */
	private $entityName;

	/**
	 * Instances
	 *
	 * @var array of SimpleTableFactory
	 */
	private static $instances = array();

	/**
	 * It create a new instance of the factory
	 *
	 * @param string $name Entity name
	 */
	private function  __construct($name) {
		$this->entityName = $name;
	}

	/**
	 * It creates a new instance of IEntityFactory
	 *
	 * @param string $name Entity name
	 * @return IEntityFactory
	 * @throws NullPointerException if the $name is empty
	 */
	public static function createEntityFactory($name) {
		if (empty($name)) {
			throw new NullPointerException("name");
		}
		$name = ucfirst($name);
		if (empty(self::$instances[$name])) {
			self::$instances[$name] = new SimpleEntityFactory($name);
		}
		return self::$instances[$name];
	}

	protected function getThisEntityName(){
		return $this->entityName;
	}

}
