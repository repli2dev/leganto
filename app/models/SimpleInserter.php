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
 * This class provides creating of simple inserters
 *
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class SimpleInserter extends Worker implements IInserter
{

	/**
	 * Avaiable instances
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

	/**
	 * It creates a new instance
	 *
	 * @param string $table
	 */
	private function  __construct($table) {
		$this->table;
	}

	/**
	 * It returns an instance of IInserter which inserts entities
	 * into the specified table.
	 *
	 * @param string $table
	 * @return IInserter
	 * @throws NullPointerException if the $table is empty
	 */
    public static function createInserter($table) {
		if (empty($table)) {
			throw new NullPointerException("table");
		}
		if (empty($this->instances[$table])) {
			$this->instances[$table] = new SimpleInserter($table);
		}
		return $this->instances[$table];
	}

	public function insert(IEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		return SimpleTableModel::createTableModel($this->table)
			->insert($this->getArrayFromEntity($entity, "Save"));
	}
}
