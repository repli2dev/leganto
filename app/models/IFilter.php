<?php
/**
 * This interface should be implemented by filters.
 *
 * @author Jan Papousek
 */
interface IFilter
{

	/**
	 * It checks if the entitty is accepted
	 *
	 * @param mixed	$entity The entity which is checked.
	 * @return boolean
	 * @throws NullPointerException if the $entity is empty.
	 */
	function accepts($entity);

}

