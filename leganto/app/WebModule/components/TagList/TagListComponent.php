<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class TagListComponent extends BaseListComponent {

	private $book;

	public function formSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::TAG, Action::INSERT)) {
			$this->unauthorized();
		}
		if (!isset($this->book)) {
			throw new InvalidStateException("The book is not set.");
		}
		$values = $form->getValues();
		$tags = explode(", ",$values["tag"]);
		$tagsEntities = array();
		try {
			foreach($tags as $tag) {
				$tagEntity = Leganto::tags()->createEmpty();
				$tagEntity->name = $tag;
				$tagEntity->languageId = System::user()->idLanguage;
				$tagsEntities[] = $tagEntity;
				$tagEntity->persist();
				unset($tagEntity);
			}
			Leganto::books()->getUpdater()->setTagged($this->book, $tagsEntities);
			$this->getPresenter()->flashMessage(System::translate("The tag has been successfuly inserted."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
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
			->addRule(Form::FILLED, "A tag has to be filled.")
			->setOption("description",System::translate("Please seperate multiple tags with ', ' (by words - comma and space)."));

		$form->onSubmit[] = array($this, "formSubmitted");
		$form->addSubmit("insert", "Insert");

		return $form;
	}

}
