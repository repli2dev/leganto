<?php

/**
 * This factory produces simple implemtation of ITableModel.
 *
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM;

use Leganto\ORM\ATableModel,
    InvalidArgumentException,
    DibiConnection;

class SimpleTableModel extends ATableModel {

	/**
	 * The all instances created by factory method 'createTableModel'
	 *
	 * @var array
	 * @see SimpleTableModel::createTableMode()
	 */
	private static $instances = array();
	private $table;

	/**
	 * It creates a new instance
	 *
	 * @param string $table Table name.
	 * @param DibiConnection $connection
	 */
	private function __construct($table, $connection) {
		parent::__costruct($connection);
		$this->table = $table;
	}

	/**
	 * It creates a new instance of a SimpleTableModel.
	 *
	 * @param string $table Table name.
	 * @return SimpleTableModel
	 * @throws InvalidArgumentException if the $table is empty.
	 * @throws InvalidArgumentException if the $connection is empty
	 */
	public static function createTableModel($table, $connection) {
		if (empty($connection) || !$connection instanceof DibiConnection) {
			throw new InvalidArgumentException("Empty connection.");
		}
		if (empty($table)) {
			throw new InvalidArgumentException("Empty [name] of table.");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleTableModel($table, $connection);
		}
		return self::$instances[$table];
	}

	protected function tableName() {
		return $this->table;
	}

}