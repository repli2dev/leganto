<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Collections
 */



/**
 * Provides the base class for a collection that contains no duplicate elements.
 *
 * @author     David Grudl
 */
class Set extends Collection implements ISet
{


	/**
	 * Appends the specified element to the end of this collection.
	 * @param  mixed
	 * @return bool  true if this collection changed as a result of the call
	 * @throws InvalidArgumentException, NotSupportedException
	 */
	public function append($item)
	{
		$this->beforeAdd($item);

		if (is_object($item)) {
			$key = spl_object_hash($item);
			if (parent::offsetExists($key)) {
				return FALSE;
			}
			parent::offsetSet($key, $item);
			return TRUE;

		} else {
			$key = $this->search($item);
			if ($key === FALSE) {
				parent::offsetSet(NULL, $item);
				return TRUE;
			}
			return FALSE;
		}
	}



	/**
	 * Returns the index of the first occurrence of the specified element,
	 * or FALSE if this collection does not contain this element.
	 * @param  mixed
	 * @return int|FALSE
	 * @throws InvalidArgumentException
	 */
	protected function search($item)
	{
		if (is_object($item)) {
			$key = spl_object_hash($item);
			return parent::offsetExists($key) ? $key : FALSE;

		} else {
			return array_search($item, $this->getArrayCopy(), TRUE);
		}
	}



	/********************* ArrayObject cooperation ****************d*g**/



	/**
	 * Not supported (only appending).
	 */
	public function offsetSet($key, $item)
	{
		if ($key === NULL) {
			$this->append($item);
		} else {
			throw new NotSupportedException;
		}
	}



	/**
	 * Not supported.
	 */
	public function offsetGet($key)
	{
		throw new NotSupportedException;
	}



	/**
	 * Not supported.
	 */
	public function offsetExists($key)
	{
		throw new NotSupportedException;
	}



	/**
	 * Not supported.
	 */
	public function offsetUnset($key)
	{
		throw new NotSupportedException;
	}

}