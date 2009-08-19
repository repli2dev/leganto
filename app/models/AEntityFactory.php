<?php
/**
 * @author Jan Papousek, Jan Drabek
 */
abstract class AEntityFactory implements IEntityFactory, IInsertable, IUpdateable, ISelectable, IDeletable
{

	/** @var IInserter */
	private $inserter;

	/** @var IUpdater */
	private $updater;
	
	/** @var ISelector */
	private $selector;
	
	/** @var IDeleter */
	private $deleter;

	/** @return IInserter */
	protected function createInserter(){
		return $this->getEntity($this->getThisEntityName().'Inserter');
	}

	/** @return IUpdater */
	protected function createUpdater(){
		return $this->getEntity($this->getThisEntityName().'Updater');
	}
	
	/** @return ISelector */
	protected function createSelector(){
		return $this->getEntity($this->getThisEntityName().'Selector');
	}
	
	/** @return IDeleter */
	protected function createDeleter(){
		return $this->getEntity($this->getThisEntityName().'Deleter');
	}

	/** @return string */
	protected function getThisEntityName(){
		return substr(get_class($this), 0, -7);
	}

	protected function getEntity($name){
		if(!class_exists($name)){
			throw new InvalidArgumentException("The entity is not ready to be created.");
		}
		return new $name;
	}
	
	public function fetchAndCreate(IDataSource $source) {
		$row = $source->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromRow($row);
	}

	public function getInserter() {
		if (empty($this->inserter)) {
			$this->inserter = $this->createInserter();
		}
		return $this->inserter;
	}

	public function getUpdater() {
		if (empty($this->updater)) {
			$this->updater = $this->createUpdater();
		}
		return $this->updater;
	}
	
	public function getSelector() {
		if (empty($this->selector)) {
			$this->selector = $this->createSelector();
		}
		return $this->selector;
	}
	
	public function getDeleter() {
		if (empty($this->deleter)) {
			$this->deleter = $this->createDeleter();
		}
		return $this->deleter;
	}

}
