<?php

/**
 * Help section
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use Nette\Application\UI\Form,
    Leganto\DB\Factory,
    FrontModule\Components\Submenu,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    FrontModule\Forms\BaseForm;

class HelpPresenter extends BasePresenter {

	public function renderDefault() {
		$this->setPageTitle($this->translate("Help"));
		$this->setPageDescription($this->translate("Are you lost in our page or do you just miss the last puzzle? This page can answer nearly all questions."));
		$this->setPageKeywords($this->translate("help, support, faq, how to, actions, how it works, tags, books, engine"));
		$language = $this->getService("environment")->language();
		$this->getTemplate()->data = Factory::supportCategory()->getSelector()->findAllSortedByWeight($language->getId());
		$content = array();
		foreach ($this->getTemplate()->data as $item) {
			$content[$item->id_support_category] = Factory::supportText()->getSelector()->findAllByCategory($item->id_support_category);
		}
		$this->getTemplate()->content = $content;
	}

	public function renderCategory($id) {
		$this->getTemplate()->category = Factory::supportCategory()->getSelector()->find($id);
		if (count($this->getTemplate()->category) == 0) {
			$this->redirect("default");
		}
		$this->setPageTitle($this->getTemplate()->category->name);
		$this->setPageDescription($this->translate("You can find all pages which belong to a chosen category on this page. Select the page which of content interests you."));
		$this->setPageKeywords($this->translate("problems, help, support, answers, how it works"));
		$this->getTemplate()->data = Factory::supportText()->getSelector()->findAllByCategory($id);
	}

	public function renderText($id) {
		$this->getTemplate()->data = Factory::supportText()->getSelector()->find($id);
		$this->setPageDescription($this->translate("You can find information you were looking for on this page, we hope ;-)"));
		$this->setPageKeywords($this->translate("help, support, answer, how it works, what to do if"));
		$this->setPageTitle($this->getTemplate()->data->name);
	}

	public function actionEditText($id) {
		if (!$this->getUser()->isAllowed(Resource::HELP, Action::EDIT)) {
			$this->unauthorized();
		} else {
			$data = Factory::supportText()->getSelector()->find($id);
			$this->getTemplate()->name = $data->name;
			$this->setPageTitle($this->translate("Edit help text") . ": " . $data->name);
			$this->setPageDescription($this->translate("You can edit text of help page."));
			$this->setPageKeywords($this->translate("edit, update, help"));
			// Set data to form
			$form = $this->getComponent("editForm");
			$data = Factory::supportText()->getSelector()->find($this->getParam("id"));
			$values["text"] = $data->text;
			$values["weight"] = $data->weight;
			$form->setDefaults($values);
		}
	}

	public function actionAddText($category) {
		if (!$this->getUser()->isAllowed(Resource::HELP, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$data = Factory::supportCategory()->getSelector()->find($category);
			$this->getTemplate()->name = $data->name;
			$this->setPageTitle($this->translate("Add help text into") . ": " . $data->name);
			$this->setPageDescription($this->translate("You can add text of help page."));
			$this->setPageKeywords($this->translate("insert, add, help"));
		}
	}

	public function actionDeleteText($id) {
		if ($this->getUser()->isAllowed(Resource::HELP, Action::EDIT)) {
			$this->setPageTitle($this->translate("Delete help page"));
			$this->setPageDescription($this->translate("You can delete a help page."));
			$this->setPageKeywords($this->translate("help, delete, remove"));
		} else {
			$this->unauthorized();
		}
	}

	protected function createComponentSubmenu($name) {
		$submenu = new Submenu($this, $name);
		$language = $this->getService("environment")->language();
		$data = Factory::supportCategory()->getSelector()->findAllSortedByWeight($language->getId());
		foreach ($data as $item) {
			$submenu->addLink("category", $item->name, array("id" => $item->id_support_category), $item->description);
		}
		if ($submenu->isCurrentUserAdmin() && $this->getAction() == "text") {
			$submenu->addEvent("editText", $this->translate("Edit"), array("id" => $this->getParam("id")));
			$submenu->addEvent("deleteText", $this->translate("Delete"), array("id" => $this->getParam("id")));
		}
		if ($submenu->isCurrentUserAdmin() && $this->getAction() == "category") {
			$submenu->addEvent("addText", $this->translate("Add"), array("category" => $this->getParam("id")));
		}
		return $submenu;
	}

	protected function createComponentEditForm($name) {
		$form = new BaseForm($this, $name);
		$form->addTextArea("text", "Page text")->getControlPrototype()->class("help-text");
		$form->addText("weight", "Weight")
			->setRequired("Please fill weight of this page.");
		$form->addSubmit("submitted", "Edit");
		$form->onSuccess[] = array($this, "editFormSubmitted");
		return $form;
	}

	public function editFormSubmitted(Form $form) {
		$values = $form->getValues();
		$id = $this->getParam("id");
		$entity = Factory::supportText()->getSelector()->find($id);
		$entity->text = $values["text"];
		$entity->weight = $values["weight"];
		$entity->persist();
		$this->flashMessage($this->translate("Help text has been successfully updated."));
		$this->redirect("text", $id);
	}

	protected function createComponentAddForm($name) {
		$form = new BaseForm($this, $name);
		$form->addText("name", "Name")
			->setRequired("Please fill the name of page.");
		$form->addTextArea("text", "Page text")->getControlPrototype()->class("help-text");
		$form->addText("weight", "Weight")
			->setRequired("Please fill weight of page.");
		$form->addSubmit("submitted", "Add");
		$form->onSuccess[] = array($this, "addFormSubmitted");
		return $form;
	}

	public function addFormSubmitted(Form $form) {
		$values = $form->getValues();
		$category = $this->getParam("category");
		$entity = Factory::supportText()->createEmpty();
		$entity->text = $values["text"];
		$entity->weight = $values["weight"];
		$entity->name = $values["name"];
		$entity->id_support_category = $category;
		$id = Factory::supportText()->getInserter()->insert($entity);
		$this->flashMessage($this->translate("Help text has been successfully added."));
		$this->redirect("text", $id);
	}

	protected function createComponentDeleteForm($name) {
		$form = new BaseForm($this,$name);
		$form->addSubmit("yes", "Yes");
		$form->addSubmit("no", "No");
		$form->onSuccess[] = array($this, "deleteFormSubmitted");
		return $form;
	}

	public function deleteFormSubmitted(Form $form) {
		if ($this->getUser()->isAllowed(Resource::HELP, Action::EDIT)) {
			$id = $this->getParam("id");
			if ($form["yes"]->isSubmittedBy()) {
				$data = Factory::supportText()->getDeleter()->delete($id);
				$this->flashMessage($this->translate("The help text has been successfully deleted."), 'success');
				$this->redirect("default");
			} else {
				$this->redirect("text", $id);
			}
		} else {
			$this->unauthorized();
		}
	}

}
