<?php
class RelatedBookListComponent extends BaseListComponent
{

    protected function beforeRender() {
	// Books and covers
	$this->getTemplate()->books = array();
	$this->getTemplate()->covers = array();
	$storage = new EditionImageStorage();
	while($book = Leganto::books()->fetchAndCreate($this->getSource())) {
	    $this->getTemplate()->books[] = $book;
	    $this->getTemplate()->covers[$book->getId()] = $storage->getRandomFileByBook($book);
	}
    }

}