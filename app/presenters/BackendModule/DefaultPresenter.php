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
		$template->introduction = Locales::get()->get("introduction");
		$template->type = Locales::get()->get("backend");
	}

}
?>
