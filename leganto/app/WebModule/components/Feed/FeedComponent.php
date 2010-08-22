<?php
class FeedComponent extends BaseListComponent {

	/** @persistent */
	public $allSwitcher;

	public function handleAll() {
		$this->allSwitcher = TRUE;
	}

	public function handleFollowed() {
		$this->allSwitcher = FALSE;
	}

	protected function beforeRender() {
		parent::beforeRender();
		$this->getTemplate()->allSwitcher = $this->allSwitcher;
		if (empty($this->getTemplate()->allSwitcher)) {
			$users = Leganto::users()->getSelector()->findAllFollowed(System::user())->fetchPairs("id_user", "id_user");
			if (empty($users)) {
				$this->flashMessage(System::translate("Sorry. No events found, probably you do not follow any user."), "error");
				$this->handleAll();
				$this->beforeRender();
			}
			else {
				$this->getSource()->where("id_user IN %l", $users);
			}
		}
		$paginator = $this->getPaginator();
		$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->feed = Leganto::feed()->fetchAndCreateAll($this->getSource());

	}

	protected function createComponentFlashMessages($name) {
		return new FlashMessagesComponent($this, $name);
	}

	protected function startUp() {
		$this->setSource(Leganto::feed()->getSelector()->findAll());
	}
}
