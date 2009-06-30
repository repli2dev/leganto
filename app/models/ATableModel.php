<?php
/**
 * Abstract class designed to be extended by classes
 * representing model on the MySQL tables.
 *
 * The MySQL table which is represented by model extending this abstract class
 * has to have just one (numeric) primary key and all required columns
 * has to be marked as "NOT NULL".
 *
 * If the MySQL table does not use this schema, you should not extend this class,
 * nut you should implement interface 'ITableModel'.
 *
 * All classes which extend this abstract class should declare
 * static method 'getTable()' which is used by other classes.
 *
 * @author Jan Papousek
 * @uses ITableModel
 */
abstract class ATableModel extends Object implements ITableModel
{
	/**
	 * The primary key of the table which is represented by this model.
	 *
	 * @var DibiColumnInfo
	 */
	private $identificator;

	/**
	 * Names of the required columns of the table which is represeted by this model.
	 *
	 * @var array|string
	 */
	private $required;


	/**
	 * It deletes an entity from database.
	 *
	 * @param int $id The identificator of the entity.
	 * @throws NullPointerException if the $id is empty.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function delete($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$deleted = dibi::delete($this->tableName())
			->where("[".$this->getIdentificator()."] = %i",$id)
			->execute();
		if ($deleted < 1) {
			throw new DataNotFoundException("id");
		}
	}

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function get() {
		return dibi::dataSource("SELECT * FROM %n", $this->tableName());
	}

	/**
	 * It returns a name of column which represents an identificator
	 * of the entity.
	 *
	 * This method is probably used just by other method of this abstract class.
	 *
	 * @return string The identificator column name.
	 */
	protected function identificator() {
		if (empty($this->identificator)) {
			$primaries = dibi::getDatabaseInfo()
				->getTable($this->tableName())
				->getPrimaryKey()
				->getColumns();
			foreach ($primaries AS $primary) {
				$this->identificator = $primary;
				break;
			}
		}
		return $this->identificator->getName();
	}

	/**
	 * It inserts an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database
	 *		or '-1' if the entity has already existed.
	 * @return InvalidArgumentException if the $input is not an array.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DataNotFoundException if there is a foreign key on not existing entity.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function insert(array $input) {
		$required = $this->requiredColumns();
		foreach ($required AS $key) {
			if (empty($input[$key])) {
				throw new NullPointerException("input[$key]");
			}
		}
		try {
			dibi::insert($this->tableName(), $input)->execute();
			return dibi::insertId();
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			switch ($e->getCode()) {
				case 1062:
					return (-1);
					break;
				// FIXME: hardcoded
				case 1216:
					throw new DataNotFoundException($e);
					break;
				default:
					throw new DibiDriverException($e);
					break;
			}
		}
	}

	/**
	 * It returns names of all required
	 * columns of MySQl table which the model work with.
	 *
	 * This method is probably used just by methods of this abstract class.
	 *
	 * @return array|string Names of required columns
	 */
	protected function requiredColumns() {
		if (empty($this->required)) {
			$this->required = array();
			$columns = dibi::getDatabaseInfo()->getTable($this->tableName())->getColumns();
			foreach ($columns AS $column) {
				if (!$column->isNullable() && $column->getName() != $this->identificator()) {
					$this->required[] = $column->getName();
				}
			}
		}
		return $this->required;
	}

	/**
	 * It returns a name of the MySQL table which the model work on.
	 *
	 * This method is probably used just by methods of this abstract class.
	 *
	 * @return string Table name
	 */
	abstract protected function tableName();

	/**
	 * It updates en entity in the database.
	 *
	 * @param int $id The identificator of the entity.
	 * @param array|mixed $input	The new data describig entity,
	 *		array keys are columns name of the table in database
	 *		and values are the content.
	 * @return boolean It return TRUE if the entity was changed,
	 *		otherwise FALSE.
	 * @throws InvalidArgumentException if the $input is not an array.
	 * @throws NullPointerException if $id is empty.
	 * @throws DataNotFoundException if the entity does not exist
	 *		or there is the foreign key on the intity which does not exist.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function update($id, array $input) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$rows = $this->get()->where("[".$this->identificator()."] = %i", $id);
		if ($rows->count() == 0) {
			throw new DataNotFoundException("id");
		}
		try {
			dibi::update($this->tableName(), $input)->execute();
			return TRUE;
		}
		catch (DibiDriverException $e) {
			Debug::processException($e);
			switch ($e->getCode()) {
				case 1062:
					return FALSE;
					break;
				// FIXME: hardcoded
				case 1216:
					throw new DataNotFoundException($e);
					break;
				default:
					throw new DibiDriverException($e);
					break;
			}
		}
	}

}