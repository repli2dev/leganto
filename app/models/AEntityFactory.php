<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
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
	protected function createInserter() {
		$inserter = $this->getThisEntityName().'Inserter';
		if (class_exists($inserter)) {
			return $this->getInstanceOfClassByName($inserter);
		}
		else {
			return SimpleInserter::createInserter(String::lower($this->getThisEntityName()));
		}
	}

	/** @return IUpdater */
	protected function createUpdater(){
		$updater = $this->getThisEntityName().'Updater';
		if (class_exists($updater)) {
			return $this->getInstanceOfClassByName($updater);
		}
		else {
			return SimpleUpdater::createUpdater(String::lower($this->getThisEntityName()));
		}
	}
	
	/** @return ISelector */
	protected function createSelector(){
		return $this->getInstanceOfClassByName($this->getThisEntityName().'Selector');
	}
	
	/** @return IDeleter */
	protected function createDeleter() {
		$deleter = $this->getThisEntityName().'Deleter';
		if (class_exists($deleter)) {
			return $this->getInstanceOfClassByName($deleter);
		}
		else {
			return SimpleDeleter::createDeleter(String::lower($this->getThisEntityName()));
		}
	}

	/** @return string */
	protected function getThisEntityName(){
		return substr(get_class($this), 0, -7);
	}

	private function getInstanceOfClassByName($name){
		return new $name;
	}

	/** @return IEntity */
	public function createEmpty() {
		return $this->getInstanceOfClassByName($this->getThisEntityName() . "Entity");
	}

	public function fetchAndCreate(IDataSource $source) {
		$row = $source->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromArray($row->getArrayCopy());
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
