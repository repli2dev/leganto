<?php
class OpinionListComponent extends BaseListComponent
{

    /** @persistent */
    public $showedOpinion;

    public function handleShowPosts($opinion) {
	$this->getTemplate()->showedOpinion = $opinion;
        $this->showedOpinion = $opinion;
        $this->invalidateControl("opinion-list");
    }

    public function handleSortByScore() {
	$this->sort("rating");
    }

    public function handleSortByTime() {
	$this->sort("inserted");
    }

    protected function beforeRender() {
	parent::beforeRender();
	$this->loadTemplate($this->getSource());
    }

    public function showBookInfo() {
        $this->getTemplate()->showedInfo = "book";
    }

    public function showPaginator($show = TRUE) {
	$this->getTemplate()->showedPaginator = $show;
    }

    public function showSorting($show = TRUE) {
	$this->getTemplate()->sorting = TRUE;
    }

    public function showUserInfo() {
        $this->getTemplate()->showedInfo = "user";
    }


    // ---- PROTECTED METHODS

    protected function createComponentPostList($name) {
	return new PostListComponent($this, $name);
    }

    // ---- PRIVATE METHODS

    private function loadTemplate(DibiDataSource $source) {
        // Default show values
        if (empty($this->getTemplate()->showedInfo)) {
            $this->getTemplate()->showedInfo = 'user';
        }
	if (!isset ($this->getTemplate()->showedPaginator )) {
		$this->getTemplate()->showedPaginator = TRUE;
	}
	// Paginator
	if ($this->getTemplate()->showedPaginator) {
		$paginator = $this->getPaginator();
		$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
	}
	else {
		$source->applyLimit($this->getLimit());
	}
	// Opinions
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
        if (!empty($this->showedOpinion)) {
            $this->getTemplate()->showedOpinion = $this->showedOpinion;
	    $this->getComponent("postList")->setSource(
		Leganto::posts()->getSelector()
		    ->findAllByIdAndType($this->getTemplate()->showedOpinion, PostSelector::OPINION)
	    );
            $this->getComponent("postList")->setDiscussed($this->showedOpinion, PostSelector::OPINION);
        }
    }

}
