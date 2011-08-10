<?php

/**
 * Interface for listener
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM\Events;

interface IListener extends \Leganto\ORM\IObject {

	function listen(IEvent $event);
}
