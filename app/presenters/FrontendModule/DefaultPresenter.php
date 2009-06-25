<?php
/**
 * The default presenter.
 *
 * @author Jan Papousek
 */
class Frontend_DefaultPresenter extends Frontend_BasePresenter
{

	public function renderDefault() {
		$template = $this->getTemplate();
		$template->introduction = Locales::get()->get("introduction");
		$template->type = Locales::get()->get("frontend");
	}

}
?>
