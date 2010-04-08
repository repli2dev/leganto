<?php
abstract class BaseListComponent extends BaseComponent
{

    /** @var int */
    private $limit = 10;

    public function getLimit() {
	return $this->limit;
    }

    public function setLimit($limit) {
	$this->limit = $limit;
    }

    public abstract function setSource(DibiDataSource $source);

    // ---- PROTECTED METHODS
    protected function createComponentPaginator($name) {
	$vp = new VisualPaginatorComponent($this, $name);
	$vp->getPaginator()->itemsPerPage = $this->getLimit();
	return $vp;
    }
}

