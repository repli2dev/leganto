<?php

use Nette\Application\UI\Presenter as Presenter,
    Leganto\Templating\Template as Template;

abstract class BasePresenter extends Presenter {

	protected function createTemplate() {
		$template = parent::createTemplate();

		return Template::loadTemplate($template);
	}

}
