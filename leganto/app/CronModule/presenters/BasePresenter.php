<?php
class Cron_BasePresenter extends BasePresenter
{
	protected function startUp() {
		parent::startup();
		$givenKey		= $this->getParam("key");
		$expectedKey	= Environment::getConfig("cron")->key;
		if (empty($givenKey) || $givenKey != $expectedKey) {
			$this->unauthorized();
		}

		if(!ini_get('safe_mode')) {
			set_time_limit(300);
			$this->flashMessage("Time limit has been set to 300s.", "success");
			ini_set('memory_limit', '128M');
			$this->flashMessage("Time limit has been set to 128M.", "success");
		}

	}

	protected function beforeRender() {
		Debug::timer("cron");
	}

	protected function createTemplate() {
		$template = parent::createTemplate();
		$template->setFile(dirname(__FILE__) . "/../templates/default.phtml");
		return $template;
	}

	protected final function unauthorized() {
		$this->flashMessage("UNAUTHORIZED", "error");
		$this->getTemplate()->render();
		$this->terminate();
	}

}
