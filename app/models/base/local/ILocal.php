<?php
/**
 * This interface is implemented by classes which represents localized texts
 * used in one module.
 *
 * @author Jan Papousek
 */
interface ILocal
{

	/**
	 * It returns a localized string or its default value.
	 *
	 * @param string $key The key name of the text.
	 * @throws NullPointerException if the $key is empty.
	 * @throws DataNotFoundException if the key was not found.
	 */
	function get($key);

}