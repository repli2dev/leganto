<?php
/**
 * Interface for basic delete operations
 * @author Jan Drabek
 */
interface IDeleter
{
	
    /**
     * @param integer $id 
	 * @return boolean
	 * @throws InvalidStateException if the record cannot be deleted
	 */
	function delete($id);

}
