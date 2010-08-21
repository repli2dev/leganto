<?php

/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class Web_HelpPresenter extends Web_BasePresenter {

	public function renderDefault() {
		$this->setPageTitle(System::translate("Help"));
		$this->setPageDescription(System::translate("Are you lost in our page, or you just miss last puzzle? This page can answer nearly all questions."));
		$this->setPageKeywords(System::translate("help, support, faq, how to, actions, how it works, tags, books, engine"));
		$this->getTemplate()->data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight();
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
		$this->setPageDescription(System::translate("On this page you can find all pages which belong to choosed category. Select page with content which bother you."));
		$this->setPageKeywords(System::translate("problems, help, support, answers, how it works"));
		$this->getTemplate()->data = Leganto::supportText()->getSelector()->findAllByCategory($id);
	}

	public function renderText($id) {
		$this->getTemplate()->data = Leganto::supportText()->getSelector()->find($id);
		$this->setPageDescription(System::translate("On this page you can find informations you were looking for, we hope ;-)"));
		$this->setPageKeywords(System::translate("help, support, answer, how it works, what to do if"));
		$this->setPageTitle($this->getTemplate()->data->name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight();
		foreach ($data as $item) {
			$submenu->addLink("category", $item->name, array("id" => $item->id_support_category));
		}
		return $submenu;
	}

}
