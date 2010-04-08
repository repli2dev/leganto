<?php
class FeedComponent extends BaseListComponent
{

    protected function beforeRender() {
	parent::beforeRender();
	$paginator = $this->getPaginator();
	$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
	$this->getTemplate()->feed = Leganto::feed()->fetchAndCreateAll($this->getSource());
    }
}
