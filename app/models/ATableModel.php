<?php
/**
 * Abstract class designed to be extended by classes
 * representing model on the MySQL tables.
 *
 * All classes which extend this abstract class should declare
 * static method 'getTable()' which is used by other classes.
 *
 * @author Jan Papousek
 */
abstract class ATableModel extends Object implements ITableModel
{
	/**
	 * It deletes an entity from database.
	 *
	 * @param int $id The identificator of the entity.
	 * @throws NullPointerException if the $id is empty.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws DibiException if there is a problem to work with database.
	 * @throws DibiException if there is a problem to work with database.
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
	 * @throws DibiException if there is a problem to work with database.
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
	abstract protected function identificator();

	/**
	 * It insert an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database
	 *		or '-1' if the entity has already existed.
	 * @throws InvalidArgumentException if the input is not an array.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function insert($input) {
		if (!is_array($input)) {
			throw new InvalidArgumentException("input");
		}
		$required = $this->requiredColumns();
		foreach ($required AS $key) {
			if (empty($input[$key])) {
				throw new NullPointerException("input[$key]");
			}
		}
		dibi::insert($this->tableName(), $input)->execute();
		return dibi::insertId();
	}

	/**
	 * It returns names of all required
	 * columns of MySQl table which the model work with.
	 *
	 * This method is probably used just by methods of this abstract class.
	 *
	 * @return array|string Names of required columns
	 */
	abstract protected function requiredColumns();

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
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws NullPointerException if $id is empty.
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function update($id, $input) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$rows = $this->get()->where("[".$this->getIdentificator()."] = %i", $id);
		if ($rows->count() == 0) {
			throw new DataNotFoundException("id");
		}
		dibi::update($this->tableName(), $input)->execute();
	}

}