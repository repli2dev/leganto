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

/*namespace Reader\Base\Files;*/

/**
 * The file name filter.
 *
 * @author      Jan Papousek
 * @version     2009-07-04
 * @package     Reader\Base\Files
 * @see         IFileFilter
 */
class FileNameFilter extends /*Nette\*/Object implements IFileFilter
{

	/**
	 * The rule describing the file name.
	 *
	 * @var string
	 */
	private $rule;

	/**
	 * It creates a new file name filter.
	 *
	 * @param string $rule The regular expression describing the file name.
	 * @throws NullPointerException if the $rule is empty.
	 */
	public function  __construct($rule) {
		if (empty($rule)) {
			throw new NullPointerException("rule");
		}
		$this->rule = $rule;
	}

	/**
	 * It returns a regular expression describing the file name.
	 *
	 * @return string
	 */
	public function getRule() {
		return $this->rule;
	}

	/**
	 * It checks if the file has supported file name.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	public function accepts(File $file) {
		if (empty($file)) {
			throw new NullPointerException("file");
		}
		return (eregi($this->getRule(), $file->getName()));
	}

}