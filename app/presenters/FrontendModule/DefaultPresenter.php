<?php
/**
 * The default presenter.
 *
 * @author Jan Papousek
 */
class Frontend_DefaultPresenter extends FrontendPresenter
{

	public function startup() {
		$this->setModule(Modules::getInstance()->get("base"));
	}

	public function renderDefault() {
		$template = $this->getTemplate();
		$template->subtitle = Locales::get()->get("introduction");
		$template->type = Locales::get()->get("frontend");
	}

}
?>
