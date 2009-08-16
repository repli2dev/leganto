<?php
/**
 * @author Jan Drabek
 */
interface IDeletable
{

	/**
	 * @return IDeleter
	 */
	function getDeleter();

}
