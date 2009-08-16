<?php
/**
 * @author Jan Papousek
 */
interface IUpdater
{

	/**
	 * @param array
	 * @return int
	 * @throws InvalidStateException if the $entity has no ID
	 */
	function update(IEntity $entity);

}
