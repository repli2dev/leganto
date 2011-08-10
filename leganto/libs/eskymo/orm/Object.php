<?php

/**
 * Object for ORM usage
 *
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM;

use Nette\Object as NetteObject,
    Leganto\ORM\IObject,
    InvalidArgumentException;

abstract class Object extends NetteObject implements IObject {

	public function getAnnotation($annotation, $attribute = NULL) {
		if (empty($annotation)) {
			throw new InvalidArgumentException("Empty annotation.");
		}
		if (empty($attribute)) {
			$reflection = $this->getReflection();
		} else {
			$reflection = $this->getReflection()->getProperty($attribute);
		}
		if (!$reflection->hasAnnotation($annotation)) {
			return NULL;
		} else {
			return $reflection->getAnnotation($annotation);
		}
	}

	public function getMethods() {
		return get_class_methods($this->getReflection()->getName());
	}

	public function getVars() {
		return array_keys(get_object_vars($this));
	}

	public function equals(Object &$object) {
		return $this === $object;
	}

}