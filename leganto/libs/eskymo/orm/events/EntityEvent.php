<?php

/**
 * Entity event
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM\Events;

use Leganto\ORM,
    Leganto\ORM\Object,
    Leganto\ORM\Events\IEvent,
    Leganto\ORM\IEntity;

class EntityEvent extends Object implements IEvent {

	/** @var IEntity */
	private $entity;

	public function __construct(IEntity &$entity) {
		$this->entity = $entity;
	}

	/** @return IEntity */
	public function getEntity() {
		return $this->entity;
	}

}
