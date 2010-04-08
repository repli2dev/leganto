<?php
class OpinionListComponent extends BaseListComponent
{

    public function handleShowPosts($opinion) {
	$this->getTemplate()->showedOpinion = $opinion;
    }

    protected function beforeRender() {
	parent::beforeRender();
	$this->loadTemplate($this->getSource());
    }

    // ---- PROTECTED METHODS

    protected function createComponentPostList($name) {
	return new PostListComponent($this, $name);
    }

    // ---- PRIVATE METHODS

    private function loadTemplate(DibiDataSource $source) {
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
	if (isset ($this->getTemplate()->showedOpinion)) {
	    $this->getComponent("postList")->setSource(
		Leganto::posts()->getSelector()
		    ->findAllByIdAndType($this->getTemplate()->showedOpinion, PostSelector::OPINION)
	    );
	}
    }

}
