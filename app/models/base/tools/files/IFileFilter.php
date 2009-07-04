<?php
/**
 * This interface is designed to be implementde by file filters.
 *
 * @author Jan Papousek
 */
interface IFileFilter
{

	/**
	 * It checks if the file is accepted.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	function accepts(File $file);

}
