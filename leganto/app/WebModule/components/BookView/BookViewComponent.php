<?php
class BookViewComponent extends BaseComponent
{

    private $book;

    private $edition;

    public function handleSetEdition($edition) {
	$this->edition = $edition;
    }

    public function getEditionId() {
	return $this->edition;
    }

    public function setBook(BookEntity $book) {
	$this->book = $book;
    }

    protected function beforeRender() {
	if (empty($this->book)) {
	    throw new InvalidArgumentException("The component [$name] can not be rendered because the book is not set.");
	}
	$this->getTemplate()->book = $this->book;
	// Authors
	$this->getTemplate()->authors = Leganto::authors()->fetchAndCreateAll(
	    Leganto::authors()->getSelector()->findAllByBook($this->book)
	);
	// Cover
	$storage = new EditionImageStorage();
	$this->getTemplate()->cover = $storage->getRandomFileByBook($this->book);
	// Editions
	$this->getComponent("editionList")->setSource(
	    Leganto::editions()->getSelector()->findAllByBook($this->book)
	);
	// Related books
	$this->getComponent("relatedBookList")->setSource(
	    Leganto::books()->getSelector()->findAllRelated($this->book)
	);
	// Edition?
	if (!empty($this->edition)) {
	    $edition = Leganto::editions()->getSelector()->find($this->edition);
	    $this->getTemplate()->edition = $edition;
	}
    }

    protected function createComponentBookStatistics($name) {
	$stats = new BookStatisticsComponent($this, $name);
	$stats->setBook($this->book);
	return $stats;
    }

    protected function createComponentEditionList($name) {
	return new EditionListComponent($this, $name);
    }

    protected function createComponentRelatedBookList($name) {
	return new RelatedBookListComponent($this, $name);
    }

    protected function createComponentTagList($name) {
	$tags = new TagListComponent($this, $name);
	$tags->setBook($this->book);
	$tags->setSource(Leganto::tags()->getSelector()->findAllByBook($this->book));
	return $tags;
    }
}
