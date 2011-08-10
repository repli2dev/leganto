<?php

/**
 * Simple implementation of updater.
 * 
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM\Workers;

use Leganto\ORM\Workers\IUpdater,
    Leganto\ORM\IEntity,
    Leganto\ORM\SimpleTableModel,
    InvalidArgumentException,
    DibiConnection;

class SimpleUpdater implements IUpdater {

	/**
	 * Available instances (for lazy init)
	 *
	 * @var array of SimpleUpdater
	 */
	private static $instances = array();

	/**
	 * Table which the updater works with
	 *
	 * @var string
	 */
	private $table;
	
	/** @var DibiConnection */
	private $connection;

	/**
	 * It creates a new instance
	 *
	 * @param string $table
	 * @param DibiConnection $connection
	 */
	private function __construct($table,$connection) {
		$this->table = $table;
		$this->connection = $connection;
	}

	/**
	 * It returns an instance of IUpdater which updates entities
	 * in the specified table.
	 *
	 * @param string $table
	 * @param DibiConnection $connection
	 * @return IUpdater
	 * @throws InvalidArgumentException if the $table is empty
	 * @throws InvalidArgumentException if the $connection is empty
	 */
	public static function createUpdater($table,$connection) {
		if (empty($connection) || ! $connection instanceof DibiConnection) {
			throw new InvalidArgumentException("Empty connection.");
		}
		if (empty($table)) {
			throw new InvalidArgumentException("Empty table name.");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleUpdater($table,$connection);
		}
		return self::$instances[$table];
	}

	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		return SimpleTableModel::createTableModel($this->table,$this->connection)
				->update($entity->getId(), $entity->getData("Save"));
	}

}
