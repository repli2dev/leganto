<?php
class UserListComponent extends BaseListComponent
{
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
	$this->getTemplate()->users = Leganto::users()->fetchAndCreateAll($source);
    }
}
