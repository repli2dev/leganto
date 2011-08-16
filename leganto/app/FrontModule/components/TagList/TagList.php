<?php

/**
 * List of tags assigned to one book
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Nette\Environment,
    Nette\InvalidStateException,
    Leganto\ACL\Action,
    Leganto\ACL\Resource,
    Leganto\DB\Factory,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Exception;

class TagList extends BaseListComponent {

	private $book;

	/**
	 * Add tag (or tags) to current book
	 * @param Form $form
	 * @return void
	 */
	public function formSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::TAG, Action::INSERT)) {
			$this->unauthorized();
		}
		if (!isset($this->book)) {
			throw new InvalidStateException("The book is not set.");
		}
		$values = $form->getValues();
		$tags = explode(", ", $values["tag"]);
		$tagsEntities = array();
		try {
			foreach ($tags as $tag) {
				$tagEntity = Factory::tag()->createEmpty();
				$tagEntity->name = $tag;
				$tagEntity->languageId = $this->getUserEntity()->idLanguage;
				$tagsEntities[] = $tagEntity;
				$tagEntity->persist();
				unset($tagEntity);
			}
			Factory::book()->getUpdater()->setTagged($this->book, $tagsEntities, $this->getContext()->getService("environment")->domain()->idLanguage);
			$this->getContext()->getService("logger")->log("ADDED " . count($tagsEntities) . " TAGS TO BOOK '" . $this->book->getId()."'");
			$this->getPresenter()->flashMessage($this->translate("The tag has been successfuly inserted."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("this");
	}

	/**
	 * Set current book
	 * @param \Leganto\DB\Book\Entity $book
	 */
	public function setBook(\Leganto\DB\Book\Entity $book) {
		$this->book = $book;
	}

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		$this->getTemplate()->tags = Factory::tag()->fetchAndCreateAll($this->getSource());
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		$form->addText("tag")
			->setRequired(Form::FILLED, "A tag has to be filled.")
			->setOption("description", $this->translate("Please seperate multiple tags with ', ' (by words - comma and space)."));

		$form->onSuccess[] = array($this, "formSubmitted");
		$form->addSubmit("insert", "Insert");

		return $form;
	}

}
