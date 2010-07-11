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

    protected function beforeRender() {
	parent::beforeRender();
	$this->loadTemplate($this->getSource());
    }

    public function showBookInfo() {
        $this->getTemplate()->showedInfo = "book";
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
        // Paginator
	$paginator = $this->getPaginator();
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
        if (!empty($this->showedOpinion)) {
            $this->getTemplate()->showedOpinion = $this->showedOpinion;
	    $this->getComponent("postList")->setSource(
		Leganto::posts()->getSelector()
		    ->findAllByIdAndType($this->getTemplate()->showedOpinion, PostSelector::OPINION)
	    );
            $this->getComponent("postList")->setDiscussed($this->showedOpinion, PostSelector::OPINION);
        }
        // Default showed info
        if (empty($this->getTemplate()->showedInfo)) {
            $this->getTemplate()->showedInfo = 'user';
        }
    }

}
