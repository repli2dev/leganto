<?php

/**
 * Inserting and editing user's opinion
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Factory,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Nette\DateTime,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Nette\ComponentModel\IContainer;

class InsertingOpinion extends BaseComponent {

	private $bookEntity;

	public function __construct(IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		// Parameters
		$book = $this->getPresenter()->getParam("book");
		// Prepare data for component
		$this->getTemplate()->book = $this->bookEntity = Factory::book()->getSelector()->find($book);
		// Test if book really exists
		if ($this->bookEntity == NULL) {
			throw new BadRequestException("Book with ID " . $book["book"] . " not found");
		}
		$this->getTemplate()->language = Factory::language()->getSelector()->find($this->bookEntity->languageId)->name;
		$this->getTemplate()->authors = Factory::author()->fetchAndCreateAll(
			Factory::author()->getSelector()
				->findAllByBook($this->bookEntity, $this->getContext()->getService("environment")->domain()->idLanguage), "Load"
		);
	}

	/**
	 * Add or edit of opinion
	 * @param Form $form
	 * @return void
	 */
	public function addOpinionFormSubmitted(Form $form) {
		// Load data from form
		$values = $form->getValues();

		// Prepare entity and persist
		$opinion = Factory::opinion()->getSelector()->findByBookAndUser($this->bookEntity, $this->getUserEntity());
		if ($opinion == NULL) {
			if (!$this->getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
				$this->unauthorized();
			}
			$opinion = Factory::opinion()->createEmpty();
			$opinion->bookTitleId = $this->bookEntity->getId();
			$message = array($this->translate("Thank you for your opinion."), 'success');
		} else {
			if (!$this->getUser()->isAllowed(Resource::create($opinion), Action::INSERT)) {
				$this->unauthorized();
			}
			$message = array($this->translate("Your opinion was successfully updated."), 'success');
		}
		$opinion->userId = $this->getUser()->getId();
		$opinion->inserted = new DateTime;
		// Needed, Nette returns NULL and ENUM value has to be '' (otherwise, the rating would be shifted)
		if ($values["rating"] == NULL) {
			$opinion->rating = 'x'; // value that cannot be inserted (hack)
		} else {
			$opinion->rating = $values["rating"];
		}
		$opinion->content = $values["content"];
		$opinion->languageId = $values["language"];
		$logger = $this->getContext()->getService("logger");
		try {
			$opinionId = $opinion->persist();
			$logger->log("INSERT/CHANGE OPINION '" . $opinion->getId() . "'");
			// Explode and add all tags
			$tags = explode(", ", $values["tags"]);
			$tagEntities = array();
			foreach ($tags as $tag) {
				$tagEntity = Factory::tag()->createEmpty();
				$tagEntity->name = $tag;
				$tagEntity->languageId = $values["language"];
				$tagEntity->persist();
				$tagEntities[] = $tagEntity;
				unset($tagEntity);
			}
			// Set tagged
			Factory::book()->getUpdater()->setTagged($this->bookEntity, $tagEntities, $values["language"]);
			$logger->log("INSERT TAGS TO BOOK '" . $this->bookEntity->getId() . "'");
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
		    0 => $this->translate("Waste"),
		    1 => $this->translate("Poor"),
		    2 => $this->translate("Fair"),
		    3 => $this->translate("Good"),
		    4 => $this->translate("Very good"),
		    5 => $this->translate("Excellent")
		);
		$form->addSelect("rating", "Rating", $ratings)
			->setOption("description", $this->translate("(empty means you really hate this book)"));
		$form->addTextArea("content", "Your opinion", 50, 15)
			->getControlPrototype()->title($this->translate("Do not write spoilers!"));
		$form->addText("tags", "Tags")->setOption("description", $this->translate("(tags will be appended to current ones)"));
		$languages = Factory::language()->getSelector()->findAll()->fetchPairs("id_language", "name");
		$form->addSelect("language", "Language", $languages)
			->setOption("description", $this->translate("(the language of your opinion, your tags)"));
		$form->addSubmit("submitted", "Add");
		$form->onSuccess[] = array($this, "addOpinionFormSubmitted");

		$opinion = Factory::opinion()->getSelector()->findByBookAndUser($this->bookEntity, $this->getUserEntity());
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
			$values["language"] = $this->getUserEntity()->idLanguage;
		}
		$form->setDefaults($values);
		return($form);
	}

}
