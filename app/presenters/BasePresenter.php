<?php
/**
 * This presenter should be extended by all presenters.
 *
 * @author Jan Papousek
 */
abstract class BasePresenter extends Presenter
{
    protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		$template->registerFilter('CurlyBracketsFilter::invoke');

		return $template;
	}
}
?>
