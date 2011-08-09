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
namespace FrontModule\Components;
use	\Leganto\System,
	Nette\ComponentModel\IContainer,
	\Nette\Environment,
	\Nette\Application\UI\Form,
	\FrontModule\Forms\BaseForm;

class Search extends BaseComponent {

	private $compact;

	public function __construct(IContainer $parent = NULL, $name = NULL, $compact = TRUE) {
		parent::__construct($parent, $name);
		$this->compact = $compact;
	}

	public function render() {
		if (!$this->compact) {
			$this->getTemplate()->setFile($this->getPath() . "SearchFull.latte");
		}
		$this->getTemplate()->form = $this->getComponent("form");
		$values["query"] = $this->getPresenter()->getparam("query");
		$values["search"] = $this->getPresenter()->getAction();
		$this->getTemplate()->form->setValues($values);
		parent::render();
	}

	public function formSubmitted(Form $form) {
		$query = $form["query"]->getValue();
		if ($this->compact) {
			$in = $form["search"]->getValue();
			if ($in == "discussion") {
				$this->getPresenter()->redirect("Search:discussion", $query);
			} else
			if ($in == "user") {
				$this->getPresenter()->redirect("Search:user", $query);
			} else
			if ($in == "help") {
				$this->getPresenter()->redirect("Search:help", $query);
			} else
			if ($in == "author") {
				$this->getPresenter()->redirect("Search:author", $query);
			} else {
				$this->getPresenter()->redirect("Search:default", $query);
			}
		} else {
			$this->getPresenter()->redirect("this", $query);
		}
	}

	protected function createComponentForm($name) {
		// AppForm used because header search form
		$form = new Form($this, $name);
		$form = BaseForm::tuneRenderer($form);
		if ($this->compact) {
			$form->getElementPrototype()->setId("search");
		}
		if ($this->compact) {
			$form->addText("query")
				->addRule(Form::FILLED, "The search field has to be filled.");
			$form["query"]->getControlPrototype()->title(System::translate("Enter query >> Select content >> Submit"));
			// Key has to be same as name of action in search presenter
			$in = array(
			    "default" => System::translate("Books"),
			    "author" => System::translate("Authors"),
			    "discussion" => System::translate("Discussions"),
			    "user" => System::translate("Users"),
			    "help" => System::translate("Help")
			);
			$form->addRadioList("search", "In", $in)
				->getControlPrototype()->setId("selectIn");
			// Remove br after pair
			$form["search"]->getSeparatorPrototype()->setName("");
			$form->addSubmit("search_submit", "Search");
		} else {
			$form->addText("query", "Text to search")
				->addRule(Form::FILLED, "The search field has to be filled.");
			$form->addSubmit("search_submit", "Search");
		}
		$form->setTranslator(System::translator());
		$form->onSubmit[] = array($this, "formSubmitted");
		$form->addProtection("Form timeout, please send form again.");
		return $form;
	}

}

