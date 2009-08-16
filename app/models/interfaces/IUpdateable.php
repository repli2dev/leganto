<?php
/**
 * @author Jan Papousek
 */
interface IUpdateable
{

	/**
	 * @return IUpdater
	 */
	function getUpdater();

}
