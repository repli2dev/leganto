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
 * This class provides creating of simple updaters
 *
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class SimpleUpdater extends Worker implements IUpdater
{

	/**
	 * Avaiable instances
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

	/**
	 * It creates a new instance
	 *
	 * @param string $table
	 */
	private function  __construct($table) {
		$this->table;
	}

	/**
	 * It returns an instance of IUpdater which updates entities
	 * in the specified table.
	 *
	 * @param string $table
	 * @return IUpdater
	 * @throws NullPointerException if the $table is empty
	 */
    public static function createUpdater($table) {
		if (empty($table)) {
			throw new NullPointerException("table");
		}
		if (empty($this->instances[$table])) {
			$this->instances[$table] = new SimpleUpdater($table);
		}
		return $this->instances[$table];
	}

	public function update(IEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		return SimpleTableModel::createTableModel($this->table)
			->update($this->getArrayFromEntity($entity, "Save"));
	}

}
