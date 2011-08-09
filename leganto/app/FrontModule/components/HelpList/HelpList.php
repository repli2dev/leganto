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
use Leganto\DB\Factory,
	DibiDataSource;
class HelpList extends BaseListComponent {

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		parent::beforeRender();
		$this->loadTemplate($this->getSource());
	}

	// ---- PRIVATE METHODS

	private function loadTemplate(DibiDataSource $source) {
		$paginator = $this->getPaginator();
		if ($this->getLimit() == 0) {
			$this->getPaginator()->itemsPerPage = $paginator->itemCount;
		}
		$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->helps = Factory::supportText()->fetchAndCreateAll($source);
	}

}
