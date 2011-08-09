<?php
/**
 * Description of EskymoObject
 *
 * @author papi
 */
namespace Leganto\ORM;

abstract class Object extends \Nette\Object implements IObject {

	public function getAnnotation($annotation, $attribute = NULL) {
		if (empty($annotation)) {
			throw new \InvalidArgumentException("annotation");
		}
		if (empty($attribute)) {
			$reflection = $this->getReflection();
		}
		else {
			$reflection = $this->getReflection()->getProperty($attribute);
		}
		if (!$reflection->hasAnnotation($annotation)) {
			return NULL;
		}
		else {
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