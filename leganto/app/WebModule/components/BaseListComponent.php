<?php
abstract class BaseListComponent extends BaseComponent
{

    /** @var int */
    private $limit = 10;

    /** @var DibiDataSource */
    private $source;

    public function getLimit() {
	return $this->limit;
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
	$this->getPaginator()->itemsPerPage = $this->getLimit();
	$this->getPaginator()->itemCount = $this->getSource()->count();
    }

    protected function createComponentPaginator($name) {
	return new VisualPaginatorComponent($this, $name);
    }

    /** @return Paginator */
    protected function getPaginator() {
	return $this->getComponent("paginator")->getPaginator();
    }

    /** @return DibiDataSource */
    protected function getSource() {
	return $this->source;
    }
}

