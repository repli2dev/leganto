<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class Web_SettingsPresenter extends Web_BasePresenter {
	public function renderDefault() {
		$this->setPageTitle(System::translate("Settings"));
	}

	protected function createComponentSettingsForm($name) {
		$form = new BaseForm;
		$form->addGroup("Basic information");
		$form->addText("nick", "Nickname")
			->addRule(Form::FILLED,"Please fill correct nickname.");
		$form->addSelect("sex", "Sex", array("" => "Unknown", "male" => "Male", "female" => "Female"));
		$form->addText("birth_year","Birth year",4)
			->addCondition(Form::FILLED)
			->addRule(Form::INTEGER,"Please correct birth year.");
		$form->addSubmit("submitted", "Finish");
		$form->onSubmit[] = array($this, "renewFormSubmitted");
		return $form;
	}
}
