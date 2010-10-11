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
class InsertingOpinionComponent extends BaseComponent {

	private $bookEntity;

	public function __construct(IComponentContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		$book = $this->getPresenter()->getParam("book");
		$this->getTemplate()->book = $this->bookEntity = Leganto::books()->getSelector()->find($book);
		if ($this->bookEntity == NULL) {
			throw new BadRequestException("Book with ID " . $book["book"] . " not found");
		}
		$this->getTemplate()->language = Leganto::languages()->getSelector()->find($this->bookEntity->languageId)->name;
		$this->getTemplate()->authors = Leganto::authors()->fetchAndCreateAll(
					Leganto::authors()->getSelector()
					->findAllByBook($this->bookEntity),
				"Load"
		);
	}

	public function addOpinionFormSubmitted(Form $form) {
		// Load data from form
		$values = $form->getValues();

		// Prepare entity and persist
		$opinion = Leganto::opinions()->getSelector()->findByBookAndUser($this->bookEntity, System::user());
		if ($opinion == NULL) {
			if (!Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
				$this->unauthorized();
			}
			$opinion = Leganto::opinions()->createEmpty();
			$opinion->bookTitleId = $this->bookEntity->getId();
			$message = array(System::translate("Thank you for your opinion, your opinion was added and book was inserted to your readed shelf."), 'success');
		} else {
			if (!Environment::getUser()->isAllowed(Resource::create($opinion), Action::INSERT)) {
				$this->unauthorized();
			}
			$message = array(System::translate("Your opinion was successfully updated."), 'success');
		}
		$opinion->userId = System::user()->getId();
		$opinion->inserted = new DateTime;
		$opinion->rating = $values["rating"];
		$opinion->content = $values["content"];
		$opinion->languageId = $values["language"];
		try {
			$opinionId = $opinion->persist();
			System::log("INSERT/CHANGE OPINION '" . $opinion->getId() . "'");
			// Explode and add all tags
			$tags = explode(", ", $values["tags"]);
			$tagEntities = array();
			foreach ($tags as $tag) {
				$tagEntity = Leganto::tags()->createEmpty();
				$tagEntity->name = $tag;
				$tagEntity->languageId = $values["language"];
				$tagEntity->persist();
				$tagEntities[] = $tagEntity;
				unset($tagEntity);
			}
			// Set tagged
			Leganto::books()->getUpdater()->setTagged($this->bookEntity, $tagEntities);
			System::log("INSERT TAGS TO BOOK '" . $this->bookEntity->getId() . "'");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->flashMessage($message[0], $message[1]);
		$this->getPresenter()->redirect("Book:default", $this->bookEntity->getId());
	}

	// PROTECTED METHODS

	protected function createComponentAddOpinionForm($name) {
		$form = new BaseForm($this, $name);
		$ratings = array(
		    0 => "---- " . System::translate("Choose") . " -----",
		    1 => System::translate("Poor"),
		    2 => System::translate("Fair"),
		    3 => System::translate("Good"),
		    4 => System::translate("Very good"),
		    5 => System::translate("Excellent")
		);
		$form->addSelect("rating", "Rating", $ratings)
			->skipFirst()
			->addRule(array($this, "validateRating"), "Please select rating.");
		$form->addTextArea("content", "Your opinion", 50, 15);
		$form->addText("tags", "Tags")->setOption("description", System::translate("(tags will be appended to current ones)"));
		$languages = Leganto::languages()->getSelector()->findAll()->fetchPairs("id_language", "name");
		$form->addSelect("language", "Language", $languages)
			->setOption("description", System::translate("(the language of your opinion, your tags)"));
		$form->addSubmit("submitted", "Add");
		$form->onSubmit[] = array($this, "addOpinionFormSubmitted");

		$opinion = Leganto::opinions()->getSelector()->findByBookAndUser($this->bookEntity, System::user());
		if ($opinion != NULL) {
			$values["rating"] = $opinion->rating;
			$values["content"] = $opinion->content;
			$values["language"] = $opinion->languageId;
			// Change text of button
			// FIXME: lepsi zpusob?
			unset($form["submitted"]);
			$form->addSubmit("submitted", "Change");
		} else {
			// Set default language of currently logged user
			$values["language"] = System::user()->idLanguage;
		}
		$form->setDefaults($values);
		return($form);
	}

	public function validateRating($control) {
		$items = $control->getItems();
		// Remove first item if we are asked to.
		if ($control->isFirstSkipped()) {
			unset($items[key($items)]);
		}
		if (isSet($items[$control->getValue()])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}