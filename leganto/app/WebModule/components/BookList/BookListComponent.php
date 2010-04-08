<?php
class BookListComponent extends BaseListComponent
{

    // ---- PROTECTED METHODS

    protected function beforeRender() {
	$this->loadTemplate($this->getSource());
    }

    protected function startUp() {
	$this->setLimit(12);
    }

    // ---- PRIVATE METHODS

    private function loadTemplate(DibiDataSource $source) {
	$paginator  = $this->getPaginator();
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
    }

}
