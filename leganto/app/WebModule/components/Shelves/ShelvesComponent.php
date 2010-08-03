<?php
class ShelvesComponent extends BaseComponent
{

    /** @var UserEntity */
    private $user;

    private $newOrder;

    private $orderedBook;

    public function handlePersist($shelf) {
	// List sent by AJAX
	$order = $this->getPresenter()->getParam("books");
	// Get shelf entity
	$shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
	// Books of shelf
	$books = Leganto::books()->fetchAndCreateAll(Leganto::books()->getSelector()->findAllByShelf($shelfEntity));
	// Update
	Leganto::shelves()->getUpdater()->changeOrder($shelfEntity, $this->getOrderedBook($order, $books), $this->getNewOrder($order, $books));
    }

    public function handleRemove($shelf) {
	try {
	    $shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
	    // TODO: Check permission
	    $shelfEntity->delete();
	    $this->getPresenter()->flashMessage(System::translate("The shelf has been successfuly deleted."), "success");
	}
	catch(Exception $e) {
	    $this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
	    error_log($e->getTraceAsString());
	    return;
	}
	$this->getPresenter()->redirect("this");
    }

    public function setUser(UserEntity $user) {
	$this->user = $user;
    }

    protected function beforeRender() {
	// Check whether the user is set
	if ($this->user == null) {
		throw new InvalidStateException("The component [$name] can not be rendered, because the user is not set.");
	}
	// Shelves
	$this->getTemplate()->shelves = Leganto::shelves()->fetchAndCreateAll(Leganto::shelves()->getSelector()->findByUser($this->user));
	// Books in shelves
	// FIXME: The books should be represented as entities
	$this->getTemplate()->books = Leganto::books()->getSelector()->findAllInShelvesByUser($this->user)->fetchAssoc("id_shelf,id_book");
	// Check whether the user is authenticated and whether he/she is owner of shelfs
	if (Environment::getUser()->isAuthenticated() && $this->user->getId() == System::user()->getId()) {
	    $this->getTemplate()->owner = TRUE;
	}
	else {
	    $this->getTemplate()->owner = FALSE;
	}
    }

    private function computeNewOrder(array $order, array $books) {
	if (count($order) != count($books)) {
	    throw new InvalidArgumentException("The list of books has different length from list of order.");
	}
	// Get ordered parts of lists 'origin' and 'new' which are different from each other
	$origin	    = array();
	$new	    = array();
	$booksById  = array();
	$diffStart  = -1;

	for($i = 0; $i < count($order); $i++) {
	    $book	= $books[$i];
	    $orderId	= $order[$i];
	    if ($book->getId() != $orderId) {
		$origin[] = $book->getId();
		$new[]	  = $orderId;
		if ($diffStart == -1) {
		    $diffStart = $i;
		}
	    }
	    $booksById[$book->getId()] = $book;
	}
	$diffEnd = $diffStart + count($origin) - 1;

	$firstDiffNew	= ExtraArray::firstValue($new);
	$lastDiffNew	= ExtraArray::lastValue($new);
	$firstDiffOrigin    = ExtraArray::firstValue($origin);
	$lastDiffOrigin    = ExtraArray::lastValue($origin);

	if ($firstDiffNew == $lastDiffOrigin) {
	    $this->orderedBook	= $booksById[$firstDiffNew];
	    $this->newOrder	= $diffStart;
	}
	else {
	    $this->orderedBook	= $booksById[$lastDiffNew];
	    $this->newOrder	= $diffEnd;
	}

    }

    private function getNewOrder(array $order, array $books) {
	if (empty($this->newOrder)) {
	    $this->computeNewOrder($order, $books);
	}
	return $this->newOrder;
    }

    private function getOrderedBook(array $order, array $books) {
	if (empty($this->orderedBook)) {
	    $this->computeNewOrder($order, $books);
	}
	return $this->orderedBook;
    }
}
