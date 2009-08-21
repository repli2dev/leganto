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
 * This class provides creating of simple deleters
 *
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class SimpleDeleter implements IDeleter
{
	/**
	 * Avaiable instances
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

	/**
	 * It creates a new instance
	 *
	 * @param string $table
	 */
	private function  __construct($table) {
		$this->table;
	}

	/**
	 * It returns an instance of IDeleter which deletes entities
	 * from the specified table.
	 *
	 * @param string $table
	 * @return IDeleter
	 * @throws NullPointerException if the $table is empty
	 */
    public static function createSimpleDeleter($table) {
		if (empty($table)) {
			throw new NullPointerException("table");
		}
		if (empty($this->instances[$table])) {
			$this->instances[$table] = new SimpleDeleter($table);
		}
		return $this->instances[$table];
	}

	public function delete($id) {
		SimpleTableModel::createTableModel($this->table)->delete($id);
	}

}
