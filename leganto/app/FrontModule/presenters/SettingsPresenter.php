<?php

/**
 * Settings presenter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use Nette\Environment,
    Leganto\DB\Factory,
    Exception,
    Nette\DateTime,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Leganto\ORM\Exceptions\DuplicityException,
    Leganto\ORM\IEntity,
    Leganto\Storage\UserIconStorage,
	Leganto\ACL\Resource,
	Leganto\ACL\Action,
	FrontModule\Components\Submenu;
	

class SettingsPresenter extends BasePresenter {

	public function renderDefault() {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create($this->getUserEntity()), Action::EDIT)) {
			$this->setPageTitle($this->translate("Settings"));
			$this->setPageDescription($this->translate("You can set your profile, write something about you, fill your age and sex on this page."));
			$this->setPageKeywords($this->translate("settings, profile, update, edit"));
		} else {
			$this->unauthorized();
		}
	}

	public function renderConnections() {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create($this->getUserEntity()), Action::EDIT)) {
			$this->setPageTitle($this->translate("Social networks"));
			$this->setPageDescription($this->translate("You can manage your social networks connected to this page here."));
			$this->setPageKeywords($this->translate("social networks, facebook, twitter, manage, edit, update, remove, add, connect"));
			$data = Factory::connection()->getSelector()->findAllFromUser($this->getUser()->id);

			$used = array();
			foreach ($data as $row) {
				$used[$row->type] = TRUE;
			}

			$this->template->data = $data;
			$this->template->used = $used;
		} else {
			$this->unauthorized();
		}
	}

	public function renderCustomization() {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create($this->getUserEntity()), Action::EDIT)) {
			$this->setPageTitle($this->translate("Customization"));
			$this->setPageDescription($this->translate("You can prepare interactive block with your latest read books for inserting into your webpage."));
			$this->setPageKeywords($this->translate("settings, profile, update, edit, customization, read books, interactive, wizard."));
		} else {
			$this->unauthorized();
		}
	}

	public function actionDelete($id) {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create($this->getUserEntity()), Action::EDIT)) {
			$this->setPageTitle($this->translate("Delete connection"));
			$this->setPageDescription($this->translate("You can delete a connection to a social network on this page."));
			$this->setPageKeywords($this->translate("delete, social network, facebook, twitter, remove"));
		} else {
			$this->unauthorized();
		}
	}

	public function actionTwitter() {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create(System::user()), Action::EDIT)) {
			// Check if user have one account already
			$user = $this->getUser()->id;
			$haveOne = Factory::connection()->getSelector()->exists($user, 'twitter');
			if (!$haveOne) {
				$twitter = new TwitterBridge;
				if ($twitter->isEnabled()) {
					$twitter->doLogin();
					$token = $twitter->getToken();
					if (!empty($token)) {
						// Check if this type and token is NOT in DB. (This statement is only for printing error, because insert do not throw exception.
						$tokenExists = Factory::connection()->getSelector()->tokenExists('twitter', $token);
						if (!$tokenExists) {
							// Prepare user connection entity
							$connection = Factory::connection()->createEmpty();
							$connection->user = $user;
							$connection->type = 'twitter';
							$connection->token = $twitter->getToken();
							$connection->inserted = new DateTime();

							$logger = $this->getService("logger");
							try {
								// Commit
								Factory::connection()->getInserter()->insert($connection);
								$logger->log("INSERT CONNECTION TO TWITTER '" . $connection->getId() . "'");
							} catch (Exception $e) {
								$this->unexpectedError($e);
								return;
							}
							$this->flashMessage($this->translate('Your account was successfully added.'), 'success');
							$this->redirect('connections');
						} else {
							$this->flashMessage($this->translate('Sorry, this account has been already connected to a different account.'), 'error');
							$this->redirect('connections');
						}
					}
				} else {
					$this->getPresenter()->flashMessage($this->translate("Twitter functions are not accessible right now. Please try it later."), "error");
					$this->getPresenter()->redirect("connections");
				}
			} else {
				$this->flashMessage($this->translate('You already have this type of an account.'), 'error');
				$this->redirect('connections');
			}
			exit;
		} else {
			$this->unauthorized();
		}
	}

	public function actionFacebook() {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create(System::user()), Action::EDIT)) {
			// Check if user have one account already
			$user = $this->getUser()->id;
			$haveOne = Factory::connection()->getSelector()->exists($user, 'facebook');
			if (!$haveOne) {
				$fb = new FacebookBridge;
				if ($fb->isEnabled()) {
					$fb->doLogin();
					// Check if this type and token is NOT in DB. (This statement is only for printing error, because insert do not throw exception.
					$tokenExists = Factory::connection()->getSelector()->tokenExists('facebook', $fb->getToken());
					if (!$tokenExists) {
						// Prepare user connection entity
						$connection = Factory::connection()->createEmpty();
						$connection->user = $user;
						$connection->type = 'facebook';
						$connection->token = $fb->getToken();
						$connection->inserted = new DateTime();

						$loger = $this->getService("logger");
						try {
							// Commit
							Factory::connection()->getInserter()->insert($connection);
							$logger->log("INSERT CONNECTION TO FACEBOOK '" . $connection->getId() . "'");
						} catch (Exception $e) {
							$this->unexpectedError($e);
							return;
						}

						$this->flashMessage($this->translate('Your account was successfully added.'), 'success');
						$this->redirect('connections');
					} else {
						$this->flashMessage($this->translate('Sorry, this account has been already connected to a different account.'), 'error');
						$this->redirect('connections');
					}
				} else {
					$this->getPresenter()->flashMessage($this->translate("Facebook functions are not accessible right now. Please try it later."), "error");
					$this->getPresenter()->redirect("connections");
				}
			} else {
				$this->flashMessage($this->translate('You already have this type of an account.'), 'error');
				$this->redirect('connections');
			}
			exit;
		} else {
			$this->unauthorized();
		}
	}

	// Factories
	protected function createComponentDeleteForm($name) {
		$form = new BaseForm($this,$name);
		$form->addSubmit("yes", "Yes");
		$form->addSubmit("no", "No");
		$form->onSubmit[] = array($this, "deleteFormSubmitted");
		return $form;
	}

	protected function createComponentSettingsForm($name) {
		// Prepare form
		$form = new BaseForm($this,$name);
		$form->addGroup("Basic information");
		$form->addText('email', "E-mail")
			->addRule(Form::FILLED, "Please fill the correct e-mail.")
			->addCondition(Form::FILLED)
			->addRule(Form::EMAIL, 'Please fill the e-mail in the right format (someone@somewhere.tld).');

		$form->addGroup("Extra information");
		$form->addSelect("sex", "Sex", array("" => $this->translate("Choose"), "male" => "Male", "female" => "Female"))
			->skipFirst()
			->addRule(Form::FILLED, 'Please choose your sex.');
		$form->addText("birthyear", "Birth year", 4)
			->addCondition(Form::FILLED)
			->addRule(Form::INTEGER, "Please correct the birth year. Only integer is allowed.");
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
			->addRule(Form::FILLED, "Please fill a new password.");

		$new2->addConditionOn($old, Form::FILLED)
			->addRule(Form::FILLED, "Please fill the new password to check it.");
		$new2->addCondition(Form::FILLED)
			->addRule(Form::EQUAL, "New passwords have to match. Please type them again.", $new);

		$form->addGroup("Avatar");
		$form->addFile("avatar", "Avatar")
			->addCondition(Form::FILLED)
			->addRule(Form::MIME_TYPE, "The file must be an image.", 'image/*')
			->addRule(Form::MAX_FILE_SIZE, "The avatar has to be smaller than 100 KB.", 1024 * 100);

		$form->setCurrentGroup();

		$form->addSubmit("submitted", "Save");
		$form->onSuccess[] = array($this, "settingsFormSubmitted");
		// Load data of current user for showing them
		$user = $this->getUserEntity();
		$values['email'] = $user->email;
		$values['sex'] = $user->sex;
		$values['birthyear'] = $user->birthyear;
		$values['about'] = $user->about;
		$form->setValues($values);
		return $form;
	}

	protected function createComponentSubmenu($name) {
		$submenu = new Submenu($this, $name);
		$submenu->addLink("default", $this->translate("Settings"), NULL, $this->translate("Set your profile, change password or avatar"));
		$submenu->addLink("connections", $this->translate("Social networks"), NULL, $this->translate("Join your social networks accounts"));
		$submenu->addLink("customization", $this->translate("Customization"), NULL, $this->translate("Prepare block with read books for your page"));
		return $submenu;
	}

	/* FORM SIGNALS */

	public function settingsFormSubmitted(Form $form) {
		$user = $this->getUserEntity();
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create($user), Action::EDIT)) {
			$values = $form->getValues();
			// Firstly check if user is not trying to change e-mail
			if ($user->email != $values["email"] && $user->password != UserAuthenticator::passwordHash($values["old"])) {
				$form->addError($this->translate("For changing your e-mail address you have to enter your current and new passwords."));
				return;
			}
			$user->email = $values["email"];
			$user->sex = $values["sex"];
			$user->birthyear = $values["birthyear"];
			$user->about = $values["about"];

			if (isSet($values["new"]) && !empty($values["new"])) {
				if ($user->password != UserAuthenticator::passwordHash($values["old"])) {
					$form->addError("An error occured, the password you have entered is wrong.");
					return;
				} else {
					$user->password = UserAuthenticator::passwordHash($values["new"]);
				}
			}
			$logger = $this->getService("logger");
			try {
				if ($user->getState() == IEntity::STATE_MODIFIED) {
					Factory::user()->getUpdater()->update($user);
					$logger->log("CHANGE OF SETTINGS");
					$this->flashMessage($this->translate("Your settings was saved."), "success");
				} else {
					$this->flashMessage($this->translate("Your settings was left unchanged."));
				}
				$tmpFile = $values["avatar"]->getTemporaryFile();
				if (!empty($tmpFile)) {
					$storage = new UserIconStorage();
					$storage->store($user, new File($tmpFile));
					$this->flashMessage($this->translate("The avatar has been successfuly changed."), "success");
				}
				$this->redirect("this");
			} catch (DuplicityException $e) {
				$this->flashMessage($this->translate("The e-mail is already used by another user."), "error");
			}
			//        catch(Exception $e) {
			//            Debug::fireLog($e->getMessage());
			//            $this->getPresenter()->flashMessage($this->translate('Unexpected error happened.'), "error");
			//        }
		} else {
			$this->unauthorized();
		}
	}

	public function deleteFormSubmitted(Form $form) {
		if ($this->getUser() != NULL && $this->getUser()->isAllowed(Resource::create($this->getUserEntity()), Action::EDIT)) {
			if ($form["yes"]->isSubmittedBy()) {
				$id = $this->getParam("id");
				$data = Factory::connection()->getSelector()->find($id);
				$user = $this->getUserEntity();
				$password = $user->password;
				$email = $user->email;
				// Check if user is allowed to delete connection (he/she isn't where his/her password is empty (-> means registration through SN))
				if (empty($password) || empty($email)) {
					$form->addError("Your connection cannot be deleted as you have created an account through a social network. In order to do so please set your account password and e-mail in the Settings tab first.");
				} else
				if ($data->user == $user->id) {
					Factory::connection()->getDeleter()->delete($id);
					$this->flashMessage($this->translate("The connection has been successfully deleted."), 'success');
					$this->redirect("connections");
				} else {
					$form->addError("You are not an owner. The operation could not be performed.");
				}
			} else {
				$this->redirect("connections");
			}
		} else {
			$this->unauthorized();
		}
	}

}
