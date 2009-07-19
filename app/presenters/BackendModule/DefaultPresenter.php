<?php
/**
 * The default presenter of the backend module
 *
 * @author Jan Papousek
 */
class Backend_DefaultPresenter extends BackendPresenter
{

	/**
	 * @Secured(action=edit_all)
	 */
	public function renderDefault() {
		$template = $this->getTemplate();
		$template->introduction = Locales::get()->get("introduction");
		$template->type = Locales::get()->get("backend");
	}

}
?>
