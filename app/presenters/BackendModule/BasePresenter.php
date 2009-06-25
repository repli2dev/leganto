<?php
/**
 * This class should be extended by all presenters in backend module.
 *
 * @author Jan Papousek
 */
abstract class Backend_BasePresenter extends BasePresenter
{

	public function beforeRender() {
		$this->template->title = Locales::get()->get("administration");
	}

}
?>