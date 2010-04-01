<?php
class PostListComponent extends BaseComponent
{
    /** @var int */
    private $limit = 10;

    public function getLimit() {
	return $this->limit;
    }


    public function setLimit($limit) {
	$this->limit = $limit;
    }

    public function setUp(DibiDataSource $source) {
	$this->loadTemplate($source);
    }

    // ---- PROTECTED METHODS
    protected function createComponentPaginator($name) {
	$vp = new VisualPaginatorComponent($this, $name);
	$vp->getPaginator()->itemsPerPage = $this->getLimit();
	return $vp;
    }

    // ---- PRIVATE METHODS

    private function loadTemplate(DibiDataSource $source) {
	$paginator = $this->getComponent("paginator")->getPaginator();
	$paginator->itemCount = $source->count();
	if ($this->getLimit() == 0) {
	    $vp->getPaginator()->itemsPerPage = $paginator->itemCount;

	}
	$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
	$this->getTemplate()->posts = Leganto::posts()->fetchAndCreateAll($source);
    }
}
