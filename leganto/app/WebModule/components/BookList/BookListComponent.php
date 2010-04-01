<?php
class BookListComponent extends BaseComponent
{

    /** @var int */
    private $limit = 12;

    /** @var bool */
    private $setupHappened = false;

    public function getLimit() {
	return $this->limit;
    }

    /**
     * It has to be called before setup
     * @param int $limit
     */
    public function setLimit($limit) {
	if ($this->setupHappened) {
	    throw new InvalidStateException("The method [setLimit] has to be called before setup.");
	}
	$this->limit = $limit;
    }

    public function setUp(DibiDataSource $source) {
	if ($this->setupHappened) {
	    throw new InvalidStateException("The method [setUp] can not be called more than once.");
	}
	$this->setupHappened = true;
	$this->loadTemplate($source);
    }

    // ---- PROTECTED METHODS

    protected function createComponentPaginator($name) {
	$vp = new VisualPaginatorComponent($this, $name);
	$vp->getPaginator()->itemsPerPage   = $this->limit;
	return $vp;
    }

    private function getPage() {
	if (empty($this->page)) {
	    return 0;
	}
	else {
	    return $this->page;
	}
    }

    private function loadTemplate(DibiDataSource $source) {
	$paginator  = $this->getComponent("paginator")->getPaginator();
	$paginator->itemCount = $source->count();
	if ($this->getLimit() == 0) {
	    $paginator->itemsPerPage = $paginator->itemCount;
	}
	$books	    = $source
	    ->applyLimit($paginator->itemsPerPage, $paginator->offset)
	    ->fetchAssoc("id_book");
	$this->getTemplate()->books = array();
	$this->getTemplate()->covers = array();
	$this->getTemplate()->tags = array();
	$this->getTemplate()->authors = array();
	if (empty($books)) {
	    return;
	}
	$authors    = Leganto::authors()->getSelector()->findAllByBooks(array_keys($books))
	    ->fetchAssoc("id_book,id_author");
	// Books and covers
	$storage = new EditionImageStorage();
	foreach($books as $book) {
	    // Book
	    $entity = Leganto::books()->createEmpty()->loadDataFromArray($book->getArrayCopy(), "Load");
	    $this->getTemplate()->books[] = $entity;
	    // Cover
	    $image = $storage->getRandomFileByBook($entity);
	    $this->getTemplate()->covers[$entity->getId()] = empty($image) ? NULL : $image->getAbsolutePath();
	}
	// Authors
	foreach($authors as $bookId => $authorGroup) {
	    $this->getTemplate()->authors[$bookId] = array();
	    foreach ($authorGroup AS $author) {
		$entity = Leganto::authors()->createEmpty()->loadDataFromArray($author->getArrayCopy(), "Load");
		$this->getTemplate()->authors[$bookId][] = $entity;
	    }
	}
	// Tags
//	$tags = Leganto::tags()->getSelector()
//	    ->findAll()
//	    ->where("[id_book] IN %l", array_keys($books))
//	    ->fetchAssoc("id_book");
//	foreach($tags AS $bookId => $tagGroup) {
//	    $this->getTemplate()->tags[$bookId] = array();
//	    foreach($tagGroup AS $tag) {
//		$entity = Leganto::tags()->createEmpty()->loadDataFromArray($tag->getArrayCopy(), "Load");
//		$this->getTemplate()->tags[$bookId][] = $entity;
//	    }
//	}
    }

}
