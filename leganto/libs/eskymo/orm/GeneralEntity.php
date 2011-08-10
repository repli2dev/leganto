<?php

/**
 * Implementation of some methods of entity
 * 
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\ORM;

use Leganto\ORM\Exceptions\InvalidStateException,
    Leganto\ORM\Events\IListener,
    Leganto\ORM\ListenableObject,
    Leganto\ORM\IEntity,
    Leganto\ORM\IEntityFactory,
    InvalidArgumentException,
    Leganto\ORM\Events\EntityEvent;

abstract class GeneralEntity extends ListenableObject implements IEntity {

	/** @var mixed */
	private $id;

	/** @var IEntityFactory */
	private $factory;

	/** @var int */
	private $state;

	// ---- PUBLIC METHODS

	public function __construct(IEntityFactory &$factory) {
		$this->factory = $factory;
		$this->state = IEntity::STATE_NEW;
	}

	public final function addOnPersistListener(IListener $listener) {
		$this->addListener(IEntity::EVENT_PERSISTED, $listener);
	}

	public final function addOnDeleteListener(IListener $listener) {
		$this->addListener(IEntity::EVENT_DELETED, $listener);
	}

	public final function delete() {
		if (!$this->getId() != null) {
			throw new InvalidStateException("The entity is not ready to be deleted.");
		}
		$this->getFactory()->getDeleter()->delete($this->getId());
		$this->callListeners(IEntity::EVENT_DELETED, new EntityEvent($this));
		$this->setState(IEntity::STATE_DELETED);
	}

	public function getId() {
		if ($this->getState() != IEntity::STATE_NEW && empty($this->id)) {
			throw new InvalidStateException("The entity has no ID.");
		}
		return $this->id;
	}

	public function getState() {
		return $this->state;
	}

	public final function loadDataFromArray(array $source, $annotation = NULL) {
		if ($this->getState() != IEntity::STATE_NEW) {
			throw new InvalidStateException("The entity is not in state [NEW]. It can't be loaded from array.");
		}
		foreach ($this->getAttributeNames($annotation) AS $var => $translated) {
			if (isset($source[$translated])) {
				$this->setAttributeValue($var, $source[$translated]);
			}
		}
		$this->loadId($source);
		return $this;
	}

	public final function persist() {
		switch ($this->getState()) {
			case IEntity::STATE_NEW:
				$id = $this->getFactory()->getInserter()->insert($this);
				$this->setId($id);
				break;
			case IEntity::STATE_MODIFIED:
				$this->getFactory()->getUpdater()->update($this);
				break;
			case IEntity::STATE_PERSISTED:
				break;
			default:
				throw new InvalidStateException("The entity can not be persisted.");
		}
		$this->setState(IEntity::STATE_PERSISTED);
		$this->callListeners(IEntity::EVENT_PERSISTED, new EntityEvent($this));
		return $this;
	}

	// ---- PROTECTED METHODS

	abstract protected function & getAttributeValue($name);

	/** @return IEntityFactory */
	protected final function getFactory() {
		return $this->factory;
	}

	/**
	 * It tries to load ID from the source
	 *
	 * @param array $source
	 */
	protected function loadId(array $source) {
		$key = $this->getIdName();
		if (isset($source[$key])) {
			$this->setId($source[$key]);
			$this->setState(IEntity::STATE_PERSISTED);
		}
	}

	abstract protected function isAttributeSet($name);

	protected final function setId($id) {
		if ($this->getId() != null) {
			throw new InvalidStateException("The entity ID has been already set. It can not be set again.");
		}
		$this->id = $id;
	}

	protected final function setState($state) {
		if (empty($state)) {
			throw new InvalidArgumentException("Empty state.");
		}
		$this->state = $state;
	}

	abstract protected function setAttributeValue($name, $value);
}
