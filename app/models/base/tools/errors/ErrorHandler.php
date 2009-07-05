<?php
/**
 * Reader's book
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        http://code.google.com/p/preader/
 * @category    Reader
 * @package     Reader\Base\Files
 * @version     2009-07-04
 */

/*namespace Reader\Base\Errors;*/

/**
 * This class provides manipulation with errors.
 *
 * @author Jan Papousek
 * @version     2009-07-04
 * @package     Reader\Base\Errors
 */
class ErrorHandler
{

	/**
	 * It sets this class as error handler
	 */
	public static function setErrorHandler() {
		set_error_handler(array(__CLASS__, 'handleError'));
	}

	/**
	 * It unserts this class as error handler.
	 */
	public static function unsetErrorHandler() {
		/*Nette\*/Debug::enable();
	}

	/**
	 * It handles errors
	 *
	 * @param int $level The error level.
	 * @param string $message The error message.
	 * @param string $file The file where the error was created.
	 * @param int $line The line of file where the error was created
	 */
	public static function handleError($level, $message, $file = NULL, $line = NULL) {
		throw new ErrorException("$file : $line : $message", $level);
	}

}
