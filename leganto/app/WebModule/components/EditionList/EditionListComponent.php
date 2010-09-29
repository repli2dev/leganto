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
class EditionListComponent extends BaseListComponent {

	/**
	 * @persistent
	 */
	public $showWithoutCover;

	public function handleHideWithoutCover() {
		$this->showWithoutCover = FALSE;
		$this->invalidateControl("editions");
	}

	public function handleShowWithoutCover() {
		$this->showWithoutCover = TRUE;
		$this->invalidateControl("editions");
	}

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		parent::beforeRender();

		$this->loadTemplate($this->getSource());
	}

	// --- PRIVATE METHODS

	private function loadTemplate(DibiDataSource $source) {
		if (!empty($this->showWithoutCover)) {
			$this->getTemplate()->editions = Leganto::editions()->fetchAndCreateAll($source);
		} else {
			$this->getTemplate()->editions = array();
			$this->getTemplate()->hidden = FALSE;
			while ($edition = Leganto::editions()->fetchAndCreate($source)) {
				if ($edition->image != NULL && file_exists(WWW_DIR . "/" . $edition->image)) {
					$this->getTemplate()->editions[] = $edition;
				} else {
					$this->getTemplate()->hidden = TRUE;
				}
			}
		}
		$this->getTemplate()->showWithoutCover = empty($this->showWithoutCover) ? FALSE : TRUE;
	}

}
