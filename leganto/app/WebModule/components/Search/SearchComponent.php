<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 *
 */

class SearchComponent extends BaseComponent {

	private $compact;

	public function  __construct(/*Nette\*/IComponentContainer $parent = NULL, $name = NULL, $compact = TRUE) {
		parent::__construct($parent, $name);
		$this->compact = $compact;
	}

	public function render() {
		if(!$this->compact){
			$this->getTemplate()->setFile($this->getPath()."searchFull.phtml");
		}
		$this->getTemplate()->form = $this->getComponent("form");
		$values["query"] = $this->getPresenter()->getparam("query");
		$this->getTemplate()->form->setValues($values);
		parent::render();
	}

	public function formSubmitted(Form $form) {
		$query = $form["query"]->getValue();
		if($this->compact){
			$this->getPresenter()->redirect("Search:default", $query);
		} else {
			$this->getPresenter()->redirect("this", $query);
		}
	}

	protected function createComponentForm($name) {
		$form = new AppForm($this,$name);
		if($this->compact){
			$form->getElementPrototype()->setId("search");
		}
		if($this->compact){
			$form->addText("query")
				->addRule(Form::FILLED, "The search field has to be filled.");
			$form->addSubmit("search_submit", "");
		} else {
			$form->addText("query","Text to search")
				->addRule(Form::FILLED, "The search field has to be filled.");
			$form->addSubmit("search_submit", "Search");
		}
		$form->onSubmit[] = array($this, "formSubmitted");
                return $form;
	}

}

