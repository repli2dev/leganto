<?php
use	Nette\Application\UI\Presenter as Presenter;

abstract class BasePresenter extends Presenter {

	protected function createTemplate() {
		$template = parent::createTemplate();

		return \Leganto\Templating\Template::loadTemplate($template);
	}	

}
