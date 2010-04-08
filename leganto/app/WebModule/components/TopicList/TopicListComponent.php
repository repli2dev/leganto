<?php
class TopicListComponent extends BaseListComponent
{

    protected function beforeRender() {
	parent::beforeRender();
	$paginator = $this->getPaginator();
	$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
	$this->getTemplate()->topics = Leganto::topics()->fetchAndCreateAll($this->getSource());
    }

}
