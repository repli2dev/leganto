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
use	DibiDataSource,
	Leganto\DB\Factory;

class AuthorList extends BaseListComponent {

	protected function beforeRender() {
		parent::beforeRender();
		$this->loadTemplate($this->getSource());
	}

	protected function startUp() {
		parent::startUp();
		$this->setLimit(12);
	}

	// ---- PRIVATE METHODS

	private function loadTemplate(DibiDataSource $source) {
		$paginator = $this->getPaginator();
		if ($this->getLimit() == 0) {
			$this->getPaginator()->itemsPerPage = $paginator->itemCount;
		}
		$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->authors = Factory::author()->fetchAndCreateAll($source);
	}

}
