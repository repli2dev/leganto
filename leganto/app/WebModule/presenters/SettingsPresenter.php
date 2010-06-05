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
	public function renderConnections(){
		$this->setPageTitle(System::translate("Social networks"));
	}

	protected function createComponentSettingsForm($name) {
		$form = new BaseForm;
		$form->addGroup("Basic information");
		$form->addText("nickname", "Nickname")
			->disabled = TRUE;
		$form->addText('email',"Email")
			->addRule(Form::FILLED,"Please fill correct email.")
			->addCondition(Form::FILLED)
			->addRule(Form::EMAIL,'Please fill email in right format someone@somewhere.tld.');

		$form->addGroup("Extra information");
		$form->addSelect("sex", "Sex", array("" => "Unknown", "male" => "Male", "female" => "Female"));
		$form->addText("birthyear","Birth year",4)
			->addCondition(Form::FILLED)
			->addRule(Form::INTEGER,"Please correct birth year. Only allowed is integer.");
		$form->addTextArea('about','About');

		// TODO: dodelat pravidla kdyz uzivatel vyplni jen new nebo new2
		$form->addGroup("Change password");
		$old = $form->addPassword("old",'Current password')
			->addCondition(Form::FILLED)
				->addRule(Form::FILLED,"Please fill in your current password");
		$form->addPassword("new",'New password')
			->addConditionOn($form["old"], Form::FILLED)
				->addRule(Form::FILLED,"Please fill your new password.");
		$form->addPassword("new2",'New password again')
			->addConditionOn($form["old"], Form::FILLED)
				->addRule(Form::FILLED,"Please fill the second password for check.")
				->addConditionOn($form["new"], Form::FILLED)
					->addRule(Form::EQUAL, "Passwords have to match!", $form["new"]);

		$form->addGroup("Avatar");
		$form->addFile("avatar","Avatar")
			->addCondition(Form::FILLED)
			->addRule(Form::MIME_TYPE,"File must be an image.",'image/*');

		$form->setCurrentGroup();

		$form->addSubmit("submitted", "Save");
		$form->onSubmit[] = array($this, "settingsFormSubmitted");
		// Load data and bind them
		$user = System::user();
		$values['nickname'] = $user->nickname;
		$values['email'] = $user->email;
		$values['sex'] = $user->sex;
		$values['birthyear'] = $user->birthyear;
		$values['about'] = $user->about;
		$form->setValues($values);
		return $form;
	}
	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("Settings"));
		$submenu->addLink("connections", System::translate("Social networks"));
		return $submenu;
	}

	/* FORM SIGNALS */
	public function settingsFormSubmitted(Form $form){
		$user = System::user();
		$values = $form->getValues();
		$user->email = $values["email"];
		$user->sex = $values["sex"];
		$user->birthyear = $values["birthyear"];
		$user->about = $values["about"];

		if(isSet($values["new"]) && !empty($values["new"])){
			$user->password = UserAuthenticator::passwordHash($values["new"]);
		}
		// TODO: ulozit avatar
		if ($user->getState() == IEntity::STATE_MODIFIED) {
			Leganto::users()->getUpdater()->update($user);
			$this->flashMessage(System::translate("Your settings was saved."));
		} else {
			$this->flashMessage(System::translate("Your settings was left unchanged."));
		}
		$this->redirect("default");
	}
}
