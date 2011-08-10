<?php

/**
 * Implementation of some methods of listenable interface
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM;

use Leganto\ORM\Exceptions,
    Leganto\ORM\Events\IListenable,
    Leganto\ORM\Events\IListener,
    Leganto\ORM\Events\IEvent,
    Leganto\ORM\Object,
    InvalidArgumentException;

abstract class ListenableObject extends Object implements IListenable {

	/** @var array */
	private $listeners = array();

	/**
	 * It adds a new listner which listens to the specified event type
	 *
	 * @param string $type Event type
	 * @param IListener $listener
	 * @throws InvalidArgumentException if type is empty
	 */
	protected final function addListener($type, IListener &$listener) {
		if (empty($type)) {
			throw new InvalidArgumentException("Empty type.");
		}
		if (!isset($this->listeners[$type])) {
			$this->listeners[$type] = array();
		}
		$this->listeners[$type][] = $listener;
	}

	/**
	 * It calls all listeners which listen to the specified
	 * @throws InvalidArgumentException if type is empty
	 */
	protected final function callListeners($type, IEvent &$event) {
		if (empty($type)) {
			throw new InvalidArgumentException("Empty type.");
		}
		if (empty($this->listeners[$type])) {
			return;
		}
		foreach ($this->listeners[$type] AS $listener) {
			$listener->listen($event);
		}
	}

}
