<?php

/**
 * The file name filter.
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @see         IFileFilter
 */

namespace Leganto\IO;

use Nette\Object,
    InvalidArgumentException;

class FileNameFilter extends Object implements IFileFilter {

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
	 * @throws InvalidArgumentException if the $rule is empty.
	 */
	public function __construct($rule) {
		if (empty($rule)) {
			throw new InvalidArgumentException("rule");
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
	 * @throws InvalidArgumentException if the $file is empty.
	 */
	public function accepts(File $file) {
		if (empty($file)) {
			throw new InvalidArgumentException("file");
		}
		return (eregi($this->getRule(), $file->getName()));
	}

}