<?php
/**
 * @author Jan Papousek
 */
interface IInserter
{
    
	/**
	 * @param array
	 * @return int
	 * @throws InvalidStateException if the $entity has been alrady inserted
	 */
	function insert(IEntity $entity);

}
