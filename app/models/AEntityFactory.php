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
	abstract protected function createInserter();

	/** @return IUpdater */
	abstract protected function createUpdater();
	
	/** @return ISelector */
	abstract protected function createSelector();
	
	/** @return IDeleter */
	abstract protected function createDeleter();
	
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
