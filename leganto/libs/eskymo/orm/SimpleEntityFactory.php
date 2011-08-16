<?php

/**
 * Implementation of entity factory
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM;

use Leganto\ORM\AEntityFactory,
    Leganto\ORM\SimpleEntityFactory,
    InvalidArgumentException,
    Nette\Utils\Strings,
    Leganto\ORM\Workers\SimpleDeleter,
    Leganto\ORM\Workers\SimpleUpdater,
    Leganto\ORM\Workers\SimpleInserter,
    DibiConnection;

class SimpleEntityFactory extends AEntityFactory {

	/**
	 * Entity name
	 *
	 * @var string
	 */
	private $entityName;
	
	/**
	 * @var DibiConnection
	 */
	private $connection;

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
	private function __construct($name,$connection) {
		$this->entityName = $name;
		$this->connection = $connection;
	}

	/** @return IEntity */
	public function createEmpty() {
		$entity = 'Leganto\DB\\' . $this->getThisEntityName() . "\Entity";
		return new $entity($this);
	}

	/**
	 * It creates a new instance of IEntityFactory
	 *
	 * @param string $name Entity name
	 * @param DibiConnection $connection DIBI connection
	 * @return IEntityFactory
	 * @throws InvalidArgumentException if the $name is empty
	 * @throws InvalidArgumentException if the $connection is empty
	 */
	public static function createEntityFactory($name, $connection) {
		if (empty($connection) || ! $connection instanceof DibiConnection) {
			throw new InvalidArgumentException("Empty connection.");
		}
		if (empty($name)) {
			throw new InvalidArgumentException("Empty name.");
		}
		$name = ucfirst($name);
		if (empty(self::$instances[$name])) {
			self::$instances[$name] = new SimpleEntityFactory($name,$connection);
		}
		return self::$instances[$name];
	}

	/* PROTECTED METHODS */

	/** @return IInserter */
	protected function createInserter() {
		$inserter = 'Leganto\DB\\' . $this->getThisEntityName() . '\Inserter';
		if (class_exists($inserter)) {
			return $this->getInstanceOfClassByName($inserter);
		} else {
			return SimpleInserter::createInserter(Strings::lower($this->getThisEntityName()),$this->connection);
		}
	}

	/** @return IUpdater */
	protected function createUpdater() {
		$updater = 'Leganto\DB\\' . $this->getThisEntityName() . '\Updater';
		if (class_exists($updater)) {
			return $this->getInstanceOfClassByName($updater);
		} else {
			return SimpleUpdater::createUpdater(Strings::lower($this->getThisEntityName()),$this->connection);
		}
	}

	/** @return ISelector */
	protected function createSelector() {
		return $this->getInstanceOfClassByName('Leganto\DB\\' . $this->getThisEntityName() . '\Selector');
	}

	/** @return IDeleter */
	protected function createDeleter() {
		$deleter = 'Leganto\DB\\' . $this->getThisEntityName() . '\Deleter';
		if (class_exists($deleter)) {
			return $this->getInstanceOfClassByName($deleter);
		} else {
			return SimpleDeleter::createDeleter(Strings::lower($this->getThisEntityName()),$this->connection);
		}
	}

	/** @return string */
	private function getInstanceOfClassByName($name) {
		return new $name($this->connection);
	}

	/** @return string */
	protected function getThisEntityName() {
		return $this->entityName;
	}

}
