<?php

/**
 * Callback listener
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM\Events;

use Leganto\ORM\Object,
    Leganto\ORM\Events\IListener,
    InvalidArgumentException,
    Leganto\ORM\Events\IEvent;

class CallbackListener extends \Leganto\ORM\Object implements IListener {

	private $callback;

	/**
	 * It creates a new callback listener
	 *
	 * @param array $callback The callback
	 */
	public function __construct(array $callback) {
		if (!is_callable($callback)) {
			throw new InvalidArgumentException("The callback is not callable!");
		}
		$this->callback = $callback;
	}

	public function listen(IEvent $event) {
		return call_user_func($this->callback, $event);
	}

}
