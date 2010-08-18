<?php
class TagListComponent extends BaseListComponent
{

    private $book;

    public function formSubmitted(Form $form) {
	if (Environment::getUser()->isAllowed(Resource::TAG, Action::INSERT)) {
	    $this->unauthorized();
	}
	if (!isset($this->book)) {
	    throw new InvalidStateException("The book is not set.");
	}
	$values = $form->getValues();
	$tag = Leganto::tags()->createEmpty();
	$tag->name = $values["tag"];
	$tag->languageId = System::user()->idLanguage;
	try {
	    $tag->persist();
	    Leganto::books()->getUpdater()->setTagged($this->book, $tag);
	    $this->getPresenter()->flashMessage(System::translate("The tag has been successfuly inserted."), "success");
	}
	catch(Exception $e) {
	    $this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
	    Debug::processException($e);
	    return;
	}
	$this->getPresenter()->redirect("this");
    }

    public function setBook(BookEntity $book) {
	$this->book = $book;
    }

    // ---- PROTECTED METHODS

    protected function beforeRender() {
	$this->getTemplate()->tags = Leganto::tags()->fetchAndCreateAll($this->getSource());
    }

    protected function createComponentForm($name) {
	$form = new BaseForm($this, $name);

	$form->addText("tag")
	    ->addRule(Form::FILLED, "Tag has to be filled.");

	$form->onSubmit[] = array($this, "formSubmitted");
	$form->addSubmit("insert", "Insert tag");

	return $form;
    }

}
