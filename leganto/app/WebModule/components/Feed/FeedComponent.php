<?php
class FeedComponent extends BaseComponent
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
	$paginator = $this->getComponent("paginator")->getPaginator();
	$paginator->itemCount = $source->count();
	$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
	$this->getTemplate()->feed = Leganto::feed()->fetchAndCreateAll($source);
    }


    // ---- PROTECTED METHODS
    protected function createComponentPaginator($name) {
	$vp = new VisualPaginatorComponent($this, $name);
	$vp->getPaginator()->itemsPerPage = $this->getLimit();
	return $vp;
    }

}
