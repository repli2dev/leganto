<?php

/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
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
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		} else {
			$this->setPageTitle(System::translate("Settings"));
		}
	}

	public function renderConnections() {
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		} else {
			$this->setPageTitle(System::translate("Social networks"));
			$data = Leganto::connections()->getSelector()->findAllFromUser(System::user()->id);

			$used = array();
			foreach ($data as $row) {
				$used[$row->type] = TRUE;
			}

			$this->template->data = $data;
			$this->template->used = $used;
		}
	}

	public function actionDelete($id) {
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		} else {
			$this->setPageTitle(System::translate("Delete connection"));
		}
	}

	public function actionTwitter() {
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		} else {
			// Check if user have one account already
			$user = System::user()->id;
			$haveOne = Leganto::connections()->getSelector()->exists($user, 'twitter');
			if (!$haveOne) {
				$twitter = new TwitterBridge;
				$twitter->doLogin();
				$token = $twitter->getToken();
				if (!empty($token)) {
					// Prepare user connection entity
					$connection = Leganto::connections()->createEmpty();
					$connection->user = $user;
					$connection->type = 'twitter';
					$connection->token = $twitter->getToken();

					try {
						// Commit
						Leganto::connections()->getInserter()->insert($connection);
						System::log("INSERT CONNECTION TO TWITTER '" . $connection->getId() . "'");
					} catch (Exception $e) {
						$this->unexpectedError($e);
						return;
					}
					$this->flashMessage(System::translate('Your account was successfully added.'), 'success');
					$this->redirect('connections');
				}
			} else {
				$this->flashMessage(System::translate('You already have this type of account.'), 'error');
				$this->redirect('connections');
			}
			exit;
		}
	}

	public function actionFacebook() {
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		} else {
			// Check if user have one account already
			$user = System::user()->id;
			$haveOne = Leganto::connections()->getSelector()->exists($user, 'facebook');
			if (!$haveOne) {
				$fb = new FacebookBridge;
				$fb->doLogin();
				// Prepare user connection entity
				$connection = Leganto::connections()->createEmpty();
				$connection->user = $user;
				$connection->type = 'facebook';
				$connection->token = $fb->getToken();

				try {
					// Commit
					Leganto::connections()->getInserter()->insert($connection);
					System::log("INSERT CONNECTION TO FACEBOOK '" . $connection->getId() . "'");
				} catch (Exception $e) {
					$this->unexpectedError($e);
					return;
				}

				$this->flashMessage(System::translate('Your account was successfully added.'), 'success');
				$this->redirect('connections');
			} else {
				$this->flashMessage(System::translate('You already have this type of account.'), 'error');
				$this->redirect('connections');
			}
			exit;
		}
	}

	// Factories
	protected function createComponentDeleteForm($name) {
		$form = new BaseForm;
		$form->addSubmit("yes", "Yes");
		$form->addSubmit("no", "No");
		$form->onSubmit[] = array($this, "deleteFormSubmitted");
		return $form;
	}

	protected function createComponentSettingsForm($name) {
		// Prepare form
		$form = new BaseForm;
		$form->addGroup("Basic information");
		$form->addText('email', "Email")
			->addRule(Form::FILLED, "Please fill correct email.")
			->addCondition(Form::FILLED)
			->addRule(Form::EMAIL, 'Please fill email in right format someone@somewhere.tld.');

		$form->addGroup("Extra information");
		$form->addSelect("sex", "Sex", array("" => "Unknown", "male" => "Male", "female" => "Female"));
		$form->addText("birthyear", "Birth year", 4)
			->addCondition(Form::FILLED)
			->addRule(Form::INTEGER, "Please correct birth year. Only allowed is integer.");
		$form->addTextArea('about', 'About');

		$form->addGroup("Change password");
		$form->addPassword("old", 'Current password');
		$form->addPassword("new", 'New password');
		$form->addPassword("new2", 'New password again');
		// Get instances of all object to create cross rules!
		$old = $form["old"];
		$new = $form["new"];
		$new2 = $form["new2"];

		$old->addConditionOn($new, Form::FILLED)
			->addRule(Form::FILLED, "Please fill your current password.");
		$old->addConditionOn($new2, Form::FILLED)
			->addRule(Form::FILLED, "Please fill your current password.");

		$new->addConditionOn($old, Form::FILLED)
			->addRule(Form::FILLED, "Please fill new password.");

		$new2->addConditionOn($old, Form::FILLED)
			->addRule(Form::FILLED, "Please fill new password for check.");
		$new2->addCondition(Form::FILLED)
			->addRule(Form::EQUAL, "New passwords have to match. Please type them again.", $new);

		$form->addGroup("Avatar");
		$form->addFile("avatar", "Avatar")
			->addCondition(Form::FILLED)
			->addRule(Form::MIME_TYPE, "File must be an image.", 'image/*')
			->addRule(Form::MAX_FILE_SIZE, "Avatar has to be smaller than 100 KB.", 1024 * 100);

		$form->setCurrentGroup();

		$form->addSubmit("submitted", "Save");
		$form->onSubmit[] = array($this, "settingsFormSubmitted");
		// Load data of current user for showing them
		$user = System::user();
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

	public function settingsFormSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		}
		$user = System::user();
		$values = $form->getValues();
		$user->email = $values["email"];
		$user->sex = $values["sex"];
		$user->birthyear = $values["birthyear"];
		$user->about = $values["about"];

		if (isSet($values["new"]) && !empty($values["new"])) {
			if ($user->password != UserAuthenticator::passwordHash($values["old"])) {
				$form->addError("Error occured, current password you have entered is wrong.");
				return;
			} else {
				$user->password = UserAuthenticator::passwordHash($values["new"]);
			}
		}
		try {
			if ($user->getState() == IEntity::STATE_MODIFIED) {
				Leganto::users()->getUpdater()->update($user);
				System::log("CHANGE OF SETTINGS");
				$this->flashMessage(System::translate("Your settings was saved."), "success");
			} else {
				$this->flashMessage(System::translate("Your settings was left unchanged."));
			}
			$tmpFile = $values["avatar"]->getTemporaryFile();
			if (!empty($tmpFile)) {
				$storage = new UserIconStorage();
				$storage->store($user, new File($tmpFile));
				$this->flashMessage(System::translate("The avatar has been successfuly changed."), "success");
			}
			$this->redirect("this");
		} catch (DuplicityException $e) {
			$this->flashMessage(System::translate("The e-mail is already used by another user."), "error");
		}
//        catch(Exception $e) {
//            Debug::fireLog($e->getMessage());
//            $this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
//        }
	}

	public function deleteFormSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::create(System::user(), Action::EDIT))) {
			$this->unauthorized();
		}
		if ($form["yes"]->isSubmittedBy()) {
			$id = $this->getParam("id");
			$data = Leganto::connections()->getSelector()->find($id);
			$user = System::user();
			$password = $user->password;
			$email = $user->email;
			// Check if user is allowed to delete connection (he/she isn't where his/her password is empty (-> means registration through SN))
			if (empty($password) || empty($email)) {
				$form->addError("Your connection cannot be deleted as you have created account through social network. In order to do so please set your account password and email in Settings tab first.");
			} else
			if ($data->user == $user->id) {
				Leganto::connections()->getDeleter()->delete($id);
				$this->flashMessage(System::translate("Connection was successfully deleted."), 'success');
				$this->redirect("connections");
			} else {
				$form->addError("You are not owner. Operation could not be performed.");
			}
		} else {
			$this->redirect("connections");
		}
	}

}
