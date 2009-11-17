<?php

class BasePresenter extends Presenter
{

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		return LegantoTemplate::loadTemplate($template);
	}

	protected function startUp() {
		parent::startup();
		$this->oldModuleMode = FALSE;
	}

}
