<?php
/**
 * This interface is designed to be implemented by classes,
 * which represents model on the table in database.
 *
 * @author Jan Papousek
 */
interface ITableModel
{

	/**
	 * It delete an entity from database.
	 *
	 * @param int $id The identificator of the entity.
	 * @throws NullPointerException if the $id is empty.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws DibiException if there is a problem to work with database.
	 * @throws DibiException if there is a problem to work with database.
	 */
	function delete($id);

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiException if there is a problem to work with database.
	 */
	function get();

	/**
	 * It returns a name of column which represents an identificator
	 * of the entity.
	 *
	 * @return string The identificator column name.
	 */
	function getIdentificator();

	/**
	 * It returns table name
	 * 
	 * @return string
	 */
	function getTable();

	/**
	 * It insert an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database
	 *		or '-1' if the entity has already existed.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DibiException if there is a problem to work with database.
	 */
	function insert($input);

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
	function update($id, $input);
}
?>
