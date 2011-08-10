<?php

/**
 * Simple implementation of inserter.
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM\Workers;

use Leganto\ORM\SimpleTableModel,
    Leganto\ORM\Workers\IInserter,
    InvalidArgumentException,
    Leganto\ORM\IEntity,
    DibiConnection;

class SimpleInserter implements IInserter {

	/**
	 * Available instances (for lazy init)
	 *
	 * @var array of SimpleInserter
	 */
	private static $instances = array();

	/**
	 * Table which the inserter works with
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
	private function __construct($table, $connection) {
		$this->table = $table;
		$this->connection = $connection;
	}

	/**
	 * It returns an instance of IInserter which inserts entities
	 * into the specified table.
	 *
	 * @param string $table
	 * @return IInserter
	 * @throws InvalidArgumentException if the $table is empty
	 * @throws InvalidArgumentException if the $connection is empty
	 */
	public static function createInserter($table) {
		if (empty($connection) || !$connection instanceof DibiConnection) {
			throw new InvalidArgumentException("Empty connection.");
		}
		if (empty($table)) {
			throw new InvalidArgumentException("Empty table name.");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleInserter($table, $connection);
		}
		return self::$instances[$table];
	}

	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		$id = SimpleTableModel::createTableModel($this->table)
			->insert($entity->getData("Save"));
		return $id;
	}

}
