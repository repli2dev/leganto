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
		$template->introduction = Local::getInstance()->get("introduction");
		$template->type = Local::getInstance()->get("frontend");
	}

}
?>
