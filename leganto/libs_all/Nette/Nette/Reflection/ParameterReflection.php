<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Reflection
 */



/**
 * Reports information about a method's parameter.
 *
 * @author     David Grudl
 */
class ParameterReflection extends ReflectionParameter
{
	/** @var mixed */
	private $function;


	public function __construct($function, $parameter)
	{
		parent::__construct($this->function = $function, $parameter);
	}



	/**
	 * @return ClassReflection
	 */
	public function getClass()
	{
		return ($ref = parent::getClass()) ? new ClassReflection($ref->getName()) : NULL;
	}



	/**
	 * @return ClassReflection
	 */
	public function getDeclaringClass()
	{
		return ($ref = parent::getDeclaringClass()) ? new ClassReflection($ref->getName()) : NULL;
	}



	/**
	 * @return MethodReflection | FunctionReflection
	 */
	public function getDeclaringFunction()
	{
		return is_array($this->function) ? new MethodReflection($this->function[0], $this->function[1]) : new FunctionReflection($this->function);
	}



	/********************* Object behaviour ****************d*g**/



	/**
	 * @return ClassReflection
	 */
	public function getReflection()
	{
		return new ClassReflection($this);
	}



	public function __call($name, $args)
	{
		return ObjectMixin::call($this, $name, $args);
	}



	public function &__get($name)
	{
		return ObjectMixin::get($this, $name);
	}



	public function __set($name, $value)
	{
		return ObjectMixin::set($this, $name, $value);
	}



	public function __isset($name)
	{
		return ObjectMixin::has($this, $name);
	}



	public function __unset($name)
	{
		throw new MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");
	}

}
