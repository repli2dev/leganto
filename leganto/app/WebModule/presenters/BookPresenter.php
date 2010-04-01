<?php
class Web_BookPresenter extends Web_BasePresenter
{

    public function renderDefault($book, $edition = NULL) {
	$this->getTemplate()->book	= Leganto::books()->getSelector()->find($book);

	if ($edition) {
	    $this->getTemplate()->edition = Leganto::editions()->getSelector()->find($edition);
	}

	$this->getTemplate()->authors	= Leganto::authors()->fetchAndCreateAll(
	    Leganto::authors()->getSelector()
		->findAllByBook($this->getTemplate()->book),
	    "Load"
	);

	$this->getTemplate()->tags	= Leganto::tags()->fetchAndCreateAll(
	    Leganto::tags()->getSelector()
		->findAllByBook($this->getTemplate()->book)
	);

	$storage = new EditionImageStorage();
	$this->getTemplate()->cover	= $storage->getRandomFileByBook($this->getTemplate()->book);

	$this->getTemplate()->editions	= Leganto::editions()->fetchAndCreateAll(
	    Leganto::editions()->getSelector()->findAllByBook($this->getTemplate()->book)
	);
	$this->getComponent("opinionList")->setLimit(5);
	$this->getComponent("opinionList")->setUp(
	    Leganto::opinions()->getSelector()
		->findAllByBook($this->getTemplate()->book)
		->where("[content] != ''")
		->applyLimit(5)
	);
	$this->setPageTitle($this->getTemplate()->book->title);
    }

    public function renderOpinions($book) {
	$this->getTemplate()->book	= Leganto::books()->getSelector()->find($book);
	$this->getComponent("opinionList")->setUp(
	    Leganto::opinions()->getSelector()
		->findAllByBook($this->getTemplate()->book, System::user())
		->where("[content] != ''")
	);
	$this->setPageTitle($this->getTemplate()->book->title);
    }

    public function renderSimilar($book) {
	$this->getTemplate()->book	= Leganto::books()->getSelector()->find($book);
	$this->getComponent("similarBooks")->setUp(
	    Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit(12)
	);
	$this->setPageTitle($this->getTemplate()->book->title);
    }

    protected function createComponentOpinionList($name) {
	return new OpinionListComponent($this, $name);
    }

    protected function createComponentSimilarBooks($name) {
	return new BookListComponent($this, $name);
    }

}