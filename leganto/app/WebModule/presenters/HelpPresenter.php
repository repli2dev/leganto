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
class Web_HelpPresenter extends Web_BasePresenter {

	public function renderDefault() {
		$this->setPageTitle(System::translate("Help"));
		$this->setPageDescription(System::translate("Are you lost in our page or do you just miss the last puzzle? This page can answer nearly all questions."));
		$this->setPageKeywords(System::translate("help, support, faq, how to, actions, how it works, tags, books, engine"));
		$this->getTemplate()->data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight(System::language()->getId());
		$content = array();
		foreach ($this->getTemplate()->data as $item) {
			$content[$item->id_support_category] = Leganto::supportText()->getSelector()->findAllByCategory($item->id_support_category);
		}
		$this->getTemplate()->content = $content;
	}

	public function renderCategory($id) {
		$this->getTemplate()->category = Leganto::supportCategory()->getSelector()->find($id);
		if (count($this->getTemplate()->category) == 0) {
			$this->redirect("default");
		}
		$this->setPageTitle($this->getTemplate()->category->name);
		$this->setPageDescription(System::translate("You can find all pages which belong to a chosen category on this page. Select the page which of content interests you."));
		$this->setPageKeywords(System::translate("problems, help, support, answers, how it works"));
		$this->getTemplate()->data = Leganto::supportText()->getSelector()->findAllByCategory($id);
	}

	public function renderText($id) {
		$this->getTemplate()->data = Leganto::supportText()->getSelector()->find($id);
		$this->setPageDescription(System::translate("You can find information you were looking for on this page, we hope ;-)"));
		$this->setPageKeywords(System::translate("help, support, answer, how it works, what to do if"));
		$this->setPageTitle($this->getTemplate()->data->name);
	}

	public function actionEditText($id) {
		if (!Environment::getUser()->isAllowed(Resource::HELP, Action::EDIT)) {
			$this->unauthorized();
		} else {
			$data = Leganto::supportText()->getSelector()->find($id);
			$this->getTemplate()->name = $data->name;
			$this->setPageTitle(System::translate("Edit help text") . ": " . $data->name);
			$this->setPageDescription(System::translate("You can edit text of help page."));
			$this->setPageKeywords(System::translate("edit, update, help"));
			// Set data to form
			$form = $this->getComponent("editForm");
			$data = Leganto::supportText()->getSelector()->find($this->getParam("id"));
			$values["text"] = $data->text;
			$form->setDefaults($values);
		}
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight(System::language()->getId());
		foreach ($data as $item) {
			$submenu->addLink("category", $item->name, array("id" => $item->id_support_category));
		}
		if(System::isCurrentUserAdmin() && $this->getAction() == "text") {
			$submenu->addEvent("editText", System::translate("Upravit"),array("id" => $this->getParam("id")));
		}
		return $submenu;
	}

	protected function createComponentEditForm($name) {
		$form = new BaseForm($this, $name);
		$form->addTextArea("text","Page text")->getControlPrototype()->class("help-text");
		$form->addSubmit("submitted", "Edit");
		$form->onSubmit[] = array($this,"editFormSubmitted");
		return $form;
	}

	public function editFormSubmitted(Form $form) {
		$values = $form->getValues();
		$id = $this->getParam("id");
		$entity = Leganto::supportText()->getSelector()->find($id);
		$entity->text = $values["text"];
		Leganto::supportText()->getUpdater()->update($entity);
		$this->flashMessage(System::translate("Help text has been successfully updated."));
		$this->redirect("text",$id);
	}

}
