<?php
/**
 * @author Jan Papousek
 */
class BasePresenter extends Presenter
{

	/* ERRORS */

	public function render500() {
		ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 500 Internal server error");
		die();
	}

	public function render404() {
		ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 404 Not Found");
		die();
	}

	/* PROTECTED METHODS */

	protected function beforeRender() {
		//Header("Content-type: text/xml");
	}

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("texy", Helpers::getHelper('texy'));
		$template->registerHelper("translate", Helpers::getHelper('translate'));

		return $template;
	}

}
