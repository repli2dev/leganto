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
 */
class Web_HelpPresenter extends Web_BasePresenter {
	public function renderDefault() {
		$this->setPageTitle(System::translate("Help"));
		$this->getTemplate()->data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight();
		$content = array();
		foreach($this->getTemplate()->data as $item) {
			 $content[$item->id_support_category] = Leganto::supportText()->getSelector()->findAllByCategory($item->id_support_category);
		}
		$this->getTemplate()->content = $content;
	}

	public function renderCategory($id) {
		$this->getTemplate()->category = Leganto::supportCategory()->getSelector()->find($id);
		if(count($this->getTemplate()->category) == 0) {
			$this->redirect("default");
		}
		$this->setPageTitle($this->getTemplate()->category->name);
		$this->getTemplate()->data = Leganto::supportText()->getSelector()->findAllByCategory($id);
		
	}

	public function renderText($id) {
		$this->getTemplate()->data = Leganto::supportText()->getSelector()->find($id);
		$this->setPageTitle($this->getTemplate()->data->name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight();
		foreach($data as $item) {
			$submenu->addLink("category", $item->name, array("id" => $item->id_support_category));
		}
		return $submenu;
	}
}
