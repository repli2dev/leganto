<?php
class EditionListComponent extends BaseListComponent
{
    /**
     * @persistent
     */
    public $showWithoutCover;

    public function handleHideWithoutCover() {
	$this->showWithoutCover = FALSE;
    }

    public function handleShowWithoutCover() {
	$this->showWithoutCover = TRUE;
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
	}
	else {
	    $this->getTemplate()->editions = array();
	    while ($edition = Leganto::editions()->fetchAndCreate($source)) {
		if ($edition->image != NULL && file_exists(WWW_DIR . "/" . $edition->image)) {
		    $this->getTemplate()->editions[] = $edition;
		}
	    }
	}
	$this->getTemplate()->showWithoutCover = empty($this->showWithoutCover) ? FALSE : TRUE;
    }
}
