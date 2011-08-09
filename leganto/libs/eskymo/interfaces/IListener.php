<?php
namespace Leganto\ORM\Events;

interface IListener extends \Leganto\ORM\IObject {

	function listen(IEvent $event);

}
