<?php

/**
 * Simple implementation of deleter
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM\Workers;

use Leganto\ORM\Workers\IDeleter,
    Leganto\ORM\SimpleTableModel,
    InvalidArgumentException,
    DibiConnection;

class SimpleDeleter implements IDeleter {

	/**
	 * Available instances (for lazy init)
	 *
	 * @var array of SimpleDeleter
	 */
	private static $instances = array();

	/**
	 * Table which the deleter works with
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
	 * It returns an instance of IDeleter which deletes entities
	 * from the specified table.
	 *
	 * @param string $table
	 * @param mixed $connection
	 * @return IDeleter
	 * @throws InvalidArgumentException if the $table is empty
	 * @throws InvalidArgumentException if the $connection is empty
	 */
	public static function createDeleter($table,$connection) {
		if (empty($connection) || !$connection instanceof DibiConnection) {
			throw new InvalidArgumentException("Empty connection.");
		}
		if (empty($table)) {
			throw new InvalidArgumentException("Empty table name.");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleDeleter($table, $connection);
		}
		return self::$instances[$table];
	}

	public function delete($id) {
		SimpleTableModel::createTableModel($this->table,$this->connection)->delete($id);
	}

}
