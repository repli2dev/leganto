<?php
class Web_BookPresenter extends Web_BasePresenter
{

    public function renderView($book) {
	$this->getTemplate()->book	= Leganto::books()->getSelector()->find($book);
	$this->getTemplate()->authors	= Leganto::authors()->fetchAndCreateAll(
	    Leganto::authors()->getSelector()
		->findAllByBook($this->getTemplate()->book)
	);
	$this->getTemplate()->tags	= Leganto::tags()->fetchAndCreateAll(
	    Leganto::tags()->getSelector()
		->findAllByBook($this->getTemplate()->book)
	);
	$storage = new EditionImageStorage();
	$this->getTemplate()->cover	= $storage->getRandomFileByBook($this->getTemplate()->book);
	$this->setPageTitle(System::translate("Book view"));
    }

}