<?php
namespace Leganto\ORM\Events;
use Leganto\ORM;

class EntityEvent extends Object implements IEvent {

	/** @var IEntity */
	private $entity;

	public function  __construct(IEntity &$entity) {
		$this->entity = $entity;
	}

	/** @return IEntity */
	public function getEntity() {
		return $this->entity;
	}

}
