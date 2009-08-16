<?php
/**
 * @author Jan Papousek
 */
interface IEntityFactory
{

	/**
	 * @return DataSource
	 */
	function all();

	/**
	 * @return IEntity
	 */
	function createEmpty();

	/**
	 * @return IEntity
	 */
	function fetchAndCreate(IDataSource $source);

	/**
	 * @return IEntity
	 */
	function one($id);

}
