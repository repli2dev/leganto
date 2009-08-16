<?php
/**
 * @author Jan Papousek
 */
interface IEntity
{

	/**
	 * It returns the entity ID
	 * @return int
	 */
	function getId();

	/**
	 * It checks if the entity can be inserted.
	 * @return bool
	 */
	function isReadyToInsert();

	/**
	 * It checks if the entity can be updated
	 * @return bool
	 */
	function isReadyToUpdate();

	/**
	 * It loads the data from DibiRow
	 *
	 * WARNING: It deletes old data!
	 * @return IEntity This method is fluent.
	 */
	function loadDataFromRow(DibiRow $row);

}

