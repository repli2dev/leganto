<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
abstract class BaseListComponent extends BaseComponent {

	/** @var int */
	private $limit = 10;
	/** @persistent */
	public $orderBy;
	/** @persistent */
	public $sorting;
	/** @var DibiDataSource */
	private $source;

	public function getLimit() {
		return $this->limit;
	}

	/**
	 * It sets default sorting of the data source.
	 *
	 * @param string $column
	 * @param string $sorting
	 */
	public function setDefaultSorting($column, $sorting = 'ASC') {
		if (empty($this->orderBy)) {
			$this->sort($column, $sorting);
		}
	}

	public function setLimit($limit) {
		$this->limit = $limit;
	}

	public function setSource(DibiDataSource $source) {
		$this->source = $source;
	}

	// ---- PROTECTED METHODS
	protected function beforeRender() {
		parent::beforeRender();
		if (!empty($this->orderBy)) {
			$this->getSource()->orderBy($this->orderBy, $this->sorting);
		}
	}

	protected function createComponentPaginator($name) {
		$paginator = new VisualPaginatorComponent($this, $name);
		$paginator->paginator->itemsPerPage = $this->getLimit();
		$paginator->paginator->itemCount = $this->getSource()->count();
		return $paginator;
	}

	/** @return Paginator */
	protected function getPaginator() {
		return $this->getComponent("paginator")->getPaginator();
	}

	/** @return DibiDataSource */
	protected function getSource() {
		return $this->source;
	}

	/**
	 * This method sorts a source of the list.
	 *
	 * @param string $column	The column which is used for sorting.
	 * @param string $sorting	Direction of sorting (ASC/DESC). If it is NULL,
	 * 				the direction which is oposite to previous direction
	 * 				is used.
	 */
	protected function sort($column, $sorting = NULL) {
		$this->orderBy = $column;
		if (empty($sorting)) {
			$this->sorting = ($this->sorting == 'ASC') ? 'DESC' : 'ASC';
		} else {
			$this->sorting = $sorting;
		}
	}

}

