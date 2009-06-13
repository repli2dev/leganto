<?php
/**
 * The default presenter of the backend module
 *
 * @author Jan Papousek
 */
class Backend_DefaultPresenter extends Backend_BasePresenter
{

	public function renderDefault() {
		$template = $this->getTemplate();
		$template->introduction = Local::getInstance()->get("introduction");
		$template->type = Local::getInstance()->get("backend");
	}

}
?>
