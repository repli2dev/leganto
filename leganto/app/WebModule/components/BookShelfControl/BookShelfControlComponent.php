<?php
class BookShelfControlComponent extends BaseComponent
{

    const OPTION_CREATE_NEW_SHELF = -1;

    private $book;

    public function setBook(BookEntity $book) {
        $this->book = $book;
    }

    public function render() {
	if (!Environment::getUser()->isAuthenticated()) {
	    return;
	}
	return parent::render();
    }

    public function formSubmitted(Form $form) {
	$shelf = $form["shelf"]->getValue();
	if ($shelf == self::OPTION_CREATE_NEW_SHELF) {
	    $this->getPresenter()->redirect("User:insertShelf", System::user()->getId());
	}
	else {
	    try {
		if (empty($shelf)) {
		    Leganto::shelves()->getUpdater()->removeFromShelves(System::user(), $this->book);
		    $this->getPresenter()->flashMessage(System::translate('Tho book has been removed from the shelf.'), "success");
		}
		else {
		    $shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
		    Leganto::shelves()->getUpdater()->insertToShelf($shelfEntity, $this->book);
		    $this->getPresenter()->flashMessage(System::translate('The book has been inserted to the shelf.'), "success");
		}
	    }
	    catch(Exception $e) {
		$this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
		error_log($e->getTraceAsString());
		return;
	    }
	    $this->getPresenter()->redirect("this");
	}
    }

    protected function createComponentForm($name) {
        $user = System::user();
        if (empty($user)) {
            throw new InvalidStateException("The component [$name] in [" . $this->getName() . "] can not be created because no user is authenticated.");
        }
        $form = new BaseForm($name);

        // Get user's shelves
        $shelves = Leganto::shelves()->getSelector()->findByUser($user)->fetchPairs("id_shelf", "name")
		    + array(self::OPTION_CREATE_NEW_SHELF => "--- " . System::translate("Create a new shelf") . " ---");
        $form->addSelect("shelf", NULL, $shelves)
	    ->skipFirst("--- " . System::translate("In no shelf") . " ---");
	$form["shelf"]->getControlPrototype()->onChange = "form.submit()";

        // Check whether the user has the book in some shelf already
        $shelf = Leganto::shelves()->getSelector()->findByUserAndBook($user, $this->book);
        if (!empty($shelf)) {
            $form->setDefaults(array("shelf" => $shelf->getId()));
        }

	// Submit settings
	$form->onSubmit[] = array($this, "formSubmitted");

        return $form;
    }

}
