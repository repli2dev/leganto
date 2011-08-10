<?php

/**
 * Interface of ORM object
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM;

use Leganto\ORM\Object;

interface IObject {

	/**
	 * It returns the class annotation value. If the attribute is set,
	 * the attribute annotation value will be returned.
	 *
	 * @param string $annotation
	 * @param string $attribute
	 */
	function getAnnotation($annotation, $attribute = NULL);

	/**
	 * It returns object methods
	 *
	 * @return array Method names
	 */
	public function getMethods();

	/**
	 * It returns object variables
	 *
	 * @return array Variable names
	 */
	public function getVars();

	/**
	 * It checks if this instance is equals to another one.
	 *
	 * @param Object $object
	 * @return bool
	 * @throws InvalidArgumentException if the $object is empty
	 */
	public function equals(Object &$object);
}
