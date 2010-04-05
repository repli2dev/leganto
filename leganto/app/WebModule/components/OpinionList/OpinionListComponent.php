<?php
class OpinionListComponent extends BaseComponent
{

    /** @var int */
    private $limit = 10;

    public function getLimit() {
	return $this->limit;
    }

    public function handleShowPosts($opinion) {
	$this->getTemplate()->showedOpinion = $opinion;
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

    protected function createComponentPostList($name) {
	return new PostListComponent($this, $name);
    }

    // ---- PRIVATE METHODS

    private function loadTemplate(DibiDataSource $source) {
	$paginator = $this->getComponent("paginator")->getPaginator();
	$paginator->itemCount = $source->count();
	$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
	$this->getTemplate()->opinions = Leganto::opinions()->fetchAndCreateAll($source);
	$opinionIds = array();
	foreach($this->getTemplate()->opinions AS $opinion) {
	    $opinionIds[] = $opinion->getId();
	}
	if (!empty($opinionIds)) {
	    $this->getTemplate()->discussions = Leganto::discussions()
		->getSelector()
		->findAll()
		->where("[id_discussable] = 2 AND [id_discussed] IN %l", $opinionIds)
		->fetchPairs("id_discussed", "number_of_posts");
	}
	else {
	    $this->getTemplate()->discussions = array();
	}
	if (isset ($this->getTemplate()->showedOpinion)) {
	    $this->getComponent("postList")->setUp(
		Leganto::posts()->getSelector()
		    ->findAllByIdAndType($this->getTemplate()->showedOpinion, PostSelector::OPINION)
	    );
	}
    }

}
