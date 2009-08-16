<?php
/**
 * @author Jan Papousek
 */
interface IEntityFactory
{

	/**
	 * @return IEntity
	 */
	function createEmpty();

	/**
	 * @return IEntity
	 */
	function fetchAndCreate(IDataSource $source);

}
