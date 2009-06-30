<?php
/**
 * This interface should be implemented by classes which check a validity.
 *
 * @author Jan Papousek
 */
interface IValidator
{

	/**
	 * It checks a validity of an entity.
	 *
	 * @param mixed	$entity The entity which is checked.
	 * @return boolean
	 * @throws NullPointerException if the $entity is empty.
	 */
	function isValid($entity);

}

