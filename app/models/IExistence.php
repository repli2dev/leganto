<?php
/**
 * This interface is designed to classes which check
 * if an entity exists in database.
 * 
 * @author Jan Papousek
 */
interface IExistence
{

	/**
	 * It returns info about entity existence.
	 *
	 * @return boolean TRUE if the entity exists, otherwise FALSE.
	 */
	function exists();
}
?>
