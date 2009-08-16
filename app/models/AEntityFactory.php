<?php
/**
 * @author Jan Papousek
 */
abstract class AEntityFactory implements IEntityFactory, IInsertable, IUpdateable
{

	/** @var IInserter */
	private $inserter;

	/** @var IUpdater */
	private $updater;

	/** @return IInserter */
	abstract protected function createInserter();

	/** @return IUpdater */
	abstract protected function createUpdater();

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

}
