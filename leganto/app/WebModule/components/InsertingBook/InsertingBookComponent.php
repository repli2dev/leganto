<?php
class InsertingBookComponent extends BaseComponent
{

    private $author;

    /** @persistent */
    public $phase;

    private $state = array();

    public function  __destruct() {
	$this->persistState();
    }

    public function handleContinueToInsert($title) {
	$this->setPhase(3);
	$this->setTitle($title);
    }

    public function handleDecrementNumberOfAuthors() {
	$number = $this->getNumberOfAuthors();
	if ($number > 1) {
	    $this->setNumberOfAuthors($number - 1);
	}
    }

    public function handleIncrementNumberOfAuthors() {
	$number = $this->getNumberOfAuthors();
	$this->setNumberOfAuthors($number + 1);
    }

    public function handleNewAuthor() {
	$this->getPresenter()->redirect("Author:insert");
    }

    public function insertFormSubmitted(Form $form) {
	$this->setValues($form->getValues());
	$this->setPhase(3);
	// Insert a new author
	if (!$this->isInsertedBookRelated() && $form["newAuthor"]->isSubmittedBy()) {
	    $this->handleNewAuthor();
	}
	// Insert a new book
	elseif ($form["insert"]->isSubmittedBy()) {
	    $this->insertBook($form);
	}
	// Add or remove author
	else {
	    // Add author
	    if ($form["addAuthor"]->isSubmittedBy()) {
		$this->handleIncrementNumberOfAuthors();
	    }
	    // Remove author
	    else {
		$this->handleDecrementNumberOfAuthors();
	    }
	    $this->resetInsertForm();
	}
    }

    public function render() {
	switch($this->getPhase()) {
		default:
		case 1:
			break;
		case 2:
			$this->getTemplate()->setFile($this->getPath()."insertingBookResult.phtml");
			break;
		case 3:
			$this->getTemplate()->setFile($this->getPath()."insertingBookForm.phtml");
			break;
	}
	return parent::render();
    }

    public function searchFormSubmitted(Form $form) {
	$values = $form->getValues();

	$this->getComponent("bookList")->setSource(
		Leganto::books()->getSelector()
			->searchByColumn(BookSelector::BOOK, $values["book_title"])
	);
	$this->setTitle($values["book_title"]);
	$this->setPhase(2);
    }

    public static function sendSignalWithAuthor(AuthorEntity $author) {
	$session = Environment::getSession("insertingBook");
	$session["signal"]  = TRUE;
	$state		    = $session["state"];
	$state["author"]    = $author->getId();
	$session["state"]   = $state;
    }

    public function setBook(BookEntity $book) {
	$this->setBookNode($book->bookNode);
    }

    public function setAuthor(AuthorEntity $author) {
	$this->author = $author;
    }

    // PROTECTED METHODS

    protected function createComponentInsertForm($name) {
	$form = new BaseForm($this, $name);

	// Basic information
	$form->addGroup("Basic information");
	$form->addText("book_title", "Book title")
		->addRule(Form::FILLED, "Fill the book title!");
	$form->addText("book_subtitle", "Book subtitle");

	// Authors
	if (!$this->isInsertedBookRelated()) {
	    $form->addGroup("Authors");
	    $authors = array(NULL => "---- " . System::translate("Choose author") . " ----")
		+ Leganto::authors()->getSelector()->findAll()->orderBy("full_name")->fetchPairs("id_author", "full_name");
	    $container = $form->addContainer("authors");
	    if ($this->getAuthor() != NULL) {
		$this->cleanAuthors();
		$this->handleIncrementNumberOfAuthors();
	    }
	    for($i=0; $i < $this->getNumberOfAuthors(); $i++) {
		$last = $container->addSelect($i, "Author", $authors)
			->skipFirst()
			->addRule(Form::FILLED,"Choose the author of the book.");
	    }
	    if (isset($last)) {
		// Add text saying what to do
		$el = Html::el("span")->setClass("underForm");
		$el->setText(System::translate("If the author is not listed, please click on button New."));
		$last->setOption("description", $el);		
	    }
	    $form->addSubmit("addAuthor","Add")
		    ->getControlPrototype()->setId("addAuthor");
	    if ($this->getNumberOfAuthors() > 1) {
		$form->addSubmit("removeAuthor","Remove")
			->setValidationScope(FALSE)
			->getControlPrototype()->setId("removeAuthor");
		$form["removeAuthor"]->getControlPrototype()->setId("removeAuthor");
	    }
	    $form->addSubmit("newAuthor","New")
		    ->setValidationScope(FALSE)
		    ->getControlPrototype()->setId("newAuthor");
	}
		    
	// Language
	$form->addGroup("Other");
	$languages = Leganto::languages()->getSelector()->findAll()->fetchPairs("id_language", "name");
	$form->addSelect("language", "Language", $languages);

	// Book node
	$form->addHidden("id_book");

	// Submit button
	$form->addGroup();
	$form->addSubmit("insert","Insert book");
	$form->onSubmit[] = array($this,"insertFormSubmitted");

	// Defaults
	if ($this->getValues() == NULL) {
	    $defaults = array();
	    $defaults["language"] = System::domain()->idLanguage;
	    if ($this->getTitle() != NULL) {
		$defaults["book_title"] = $this->getTitle();
	    }
	    if ($this->getBookNode() != NULL) {
		$defaults["id_book"] = $this->getBookNode();
	    }    
	}
	else {
	    $defaults = $this->getValues();
	}
	// -1 because of indexing from 0
	if ($this->getAuthor() != NULL) {
	    $defaults["authors"] += array($this->getNumberOfAuthors() - 1 => $this->getAuthor());
	}
	$form->setDefaults($defaults);

	//$$this->resetState();

	return $form;
    }

    protected function createComponentSearchForm($name) {
	$form = new BaseForm($this, $name);

	$form->addText("book_title", "Book title")
		->addRule(Form::FILLED, "Fill the book title!")
		->addRule(Form::MIN_LENGTH, "The book title has to be at least 3 characters long.", 3);

	$form->addSubmit("submit_search", "Search");
	$form->onSubmit[] = array($this, "searchFormSubmitted");

	return $form;
    }

    protected function createComponentBookList($name) {
	return new BookListComponent($this, $name);
    }

    protected function startUp() {
	$this->loadPersistedState();
	Debug::dump($this->state);
    }

    // PRIVATE METHODS

    private function cleanAuthors() {
	$values = $this->getValues();
	if (empty($values)) {
	    return;
	}
	// Clean authors (delete empty select list)
	$authors = array();
	foreach ($values["authors"] as $row) {
		if(!empty($row)) {
			$authors[] = $row;
		}
	}
	$this->setNumberOfAuthors(count($authors));
	$values["authors"] = $authors;
	$this->setValues($values);
    }

    private function getAuthor() {
	return isset($this->state["author"]) ? $this->state["author"] : NULL;
    }

    private function getBookNode() {
	return isset($this->state["bookNode"]) ? $this->state["bookNode"] : NULL;
    }

    private function getNumberOfAuthors() {
	if (!isset($this->state["numberOfAuthors"])) {
	    $this->state["numberOfAuthors"] = 1;
	}
	return $this->state["numberOfAuthors"];
    }

    private function getPhase() {
	if (empty($this->phase)) {
	    $this->phase	    = 1;
	}
	if (empty($this->state["phase"])) {
	    $this->state["phase"]   = $this->phase;
	}
	return $this->phase;
    }

    private function getTitle() {
	return isset($this->state["title"]) ? $this->state["title"] : NULL;
    }

    private function getValues() {
	return isset($this->state["values"]) ? $this->state["values"] : NULL;
    }

    private function insertBook(Form $form) {
	$values = $form->getValues();

	// Insert book
	$book= Leganto::books()->createEmpty();
	$book->languageId = $values["language"];
	$book->title = $values["book_title"];
	$book->subtitle = $values["book_subtitle"];
	$book->inserted = new DateTime;
	if ($this->isInsertedBookRelated()) {
	    $book->bookNode = $values["id_book"];
	}
	try {
	    $book->persist();
	}
	catch(Exception $e) {
	    $this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
	    error_log($e->getTraceAsString());
	    return;
	}

	// If the book is not relation to another one, insert authors
	if (!$this->isInsertedBookRelated()) {
	    $authors = Leganto::authors()->fetchAndCreateAll(
		    Leganto::authors()->getSelector()->findAll()
			    ->where("id_author IN %l", $values["authors"])
	    );
	    try {
		Leganto::books()->getUpdater()->setWrittenBy($book, $authors);
	    }
	    catch(Exception $e) {
		$this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
		error_log($e->getTraceAsString());
		return;
	    }
	}

	// Find editions and images
	try {
	    $language = Leganto::languages()->getSelector()->find($book->languageId);
	    $imageFinder    = new EditionImageFinder();
	    $storage        = new EditionImageStorage();
	    $googleFinder   = new GoogleBooksBookFinder($language->google);
	    $info           = $googleFinder->get($book);
	    // Get editions
	    if (!empty($info)) {
		$editions = Leganto::editions()->getInserter()->insertByGoogleBooksInfo($book, $info);
	    }
	    // Try to find image foreach edition
	    foreach($editions AS $edition) {
		    // Find images
		    $images = $imageFinder->get($edition);
		    // Store first one
		    if (!empty($images)) {
			    $storage->store($edition, new File(ExtraArray::firstValue($images)));
		    }
	    }
	}
	catch(Exception $e) {
		$this->getPresenter()->flashMessage(System::translate("The book has been inserted. Unfortunate, book cover and other additional informations could not be fetched, still you can add them manually."),'info');
	}
	//$this->resetState();
	$this->getPresenter()->redirect("Book:default",$book->getId());
    }

    private function isInsertedBookRelated() {
	return ($this->getBookNode() != NULL);
    }

    private function loadPersistedState() {
	$session = Environment::getSession("insertingBook");
	if (!empty($session["signal"]) || $this->getPhase() != 1) {
	    unset($session["signal"]);
	    $this->state = $session["state"];
	    $this->setPhase($session["state"]["phase"]);
	}
	else {
	    $this->state["numberOfAuthors"] = $session["state"]["numberOfAuthors"];
	}
    }

    /**
     * Persisting of the state is needed because in phase 3 the user
     * can leave the page with component to insert a new author and come back
     * to continue in inserting the book.
     */
    private function persistState() {
	$session = Environment::getSession("insertingBook");
	$session->setExpiration(1800); // 30 minutes
	$session["state"] = $this->state;
    }

    private function resetInsertForm() {
	$form = $this->getComponent("insertForm");
	$this->removeComponent($form);
	$this->getComponent("insertForm");
    }

    private function resetNumberOfAuthors() {
	if (isset($this->state["numberOfAuthors"])) {
	    unset($this->state["numberOfAuthors"]);
	}
    }

    private function setBookNode($bookNode) {
	$this->state["bookNode"] = $bookNode;
    }

    private function setNumberOfAuthors($number) {
	$this->state["numberOfAuthors"] = $number;
    }

    private function setPhase($phase) {
	$this->phase		= $phase;
	$this->state["phase"]	= $phase;
    }

    private function setTitle($title) {
	$this->getTemplate()->title = $title;
	$this->state["title"] = $title;
    }

    private function setValues(array $values) {
	$this->state["values"] = $values;
    }
}