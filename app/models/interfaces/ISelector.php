<?php
/**
 * Interface for basic select operations, all other select operations should
 * be defined in new interface which have to inherit this one!
 * @author Jan Drabek
 */
interface ISelector 
{
	
    /**
     * @param integer $id 
	 * @return IEntity
	 */
	function findOne($id);
	
	/**
	 * @return DataSource
	 */
	function findAll();

}
