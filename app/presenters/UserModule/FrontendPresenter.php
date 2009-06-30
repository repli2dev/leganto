<?php
/**
 * This presenter provides action with users - registrate, edit etc
 *
 * @author papi
 */
class Users_FrontendPresenter extends FrontendPresenter
{

	/** @persistent */
	public $userFormType = "new"; // = 'new'/'edit'

	public function startup() {
		$this->setActions(array("edit" => "edit"));
		$this->setModule(Modules::getInstance()->get("users"));
	}

	/**
	 * It renders a users list.
	 */
	public function renderDefault() {
		$this->template->users = $this->getComponent("usersDataGrid");
	}

	/**
	 * It renders a registration form.
	 */
	public function renderRegister() {
		$this->userFormType = "new";
		$this->template->form = $this->getComponent("userInfoForm");
	}

	/**
	 * It renders a form to edit personal info of the logged user.
	 */
	public function renderEdit() {
		$this->userFormType = "edit";
		$this->template->form = $this->getComponent("userInfoForm");
	}

	/**
	 * It processes a registration form
	 */
	public function registerSubmitted(Form $form) {
		$values = $form->getValues();
		$users = new Users();
		try {
			if ($values["password_check"] !== $values[Users::DATA_PASSWORD]) {
				$form->addError(Locales::get("users")->get("wrong_check_password"));
				return;
			}
			unset($values["password_check"]);
			$domain = Site::getInstance()->getDomain();
			$values[Users::DATA_TYPE] = "common";
			$values[Users::DATA_ROLE] = $domain[Domain::DATA_DEFAULT_ROLE];
			if ($users->insert($values) == -1) {
				$form->addError(Locales::get("users")->get("user_exists"));
				return;
			}
			$this->flashMessage(Locales::get("users")->get("successful_registration"));
			$this->redirect(":Frontend:Default:");
		}
		catch (InvalidArgumentException $e) {
			$form->addError(Locales::get("users")->get("invalid_email"));
			Debug::processException($e);
		}
		catch(DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	/**
	 * It processes an editation form.
	 */
	public function editSubmitted(Form $form) {
		$values = $form->getValues();
		$users = new Users();
		try {
			if (!empty($values[Users::DATA_PASSWORD]) && $values[Users::DATA_PASSWORD] != $values["password_check"]) {
				$form->addError(Locales::get("users")->get("wrong_check_password"));
				return;
			}
			unset($values["password_check"]);
			$id = Environment::getUser()->getIdentity()->id_user;
			if (!$users->update($id, $values)) {
				$form->addError(Locales::get("users")->get("user_exists"));
				return;
			}
			$this->flashMessage(Locales::get("users")->get("user_successfully_updated"));
			// TODO: refresh identity
			// TODO: redirect
		}
		catch (InvalidArgumentException $e) {
			$form->addError(Locales::get("users")->get("invalid_email"));
			Debug::processException($e);
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	/* COMPONENTS */
	protected function createUserInfoForm($name) {
		// TODO: Dodelat editaci udaju o uzivateli.
		$form = new AppForm($this,$name);

		if ($this->userFormType == "new") {
			$form->addGroup(Locales::get("users")->get("registration"));
			// Nickname
			$form->addText(Users::DATA_NICKNAME, Locales::get("users")->get("nickname"))
				->addRule(Form::FILLED, Locales::get("users")->get("nickname"));
		}
		else {
			$form->addGroup(Locales::get("users")->get("edit_personal_info"));
		}
		// E-mail address.
		$form->addText(Users::DATA_EMAIL, Locales::get("users")->get("email"))
			->addRule(Form::FILLED, Locales::get("users")->get("email_not_filled"));
		// Password
		$form->addPassword(Users::DATA_PASSWORD, Locales::get("users")->get("password"));
		// Password check
		$form->addPassword("password_check", Locales::get("users")->get("password_check"));
		// Sex
		$options = array(
			NULL => Locales::get()->get("not_chosen"),
			"male" => Locales::get("users")->get("male"),
			"female" => Locales::get("users")->get("female")
		);
		$form->addSelect(Users::DATA_SEX, Locales::get("users")->get("sex"),$options);
		// Year of birth
		$form->addText(Users::DATA_YEAR_OF_BIRTH, Locales::get("users")->get("year_of_birth"));
		// Preferred language
		$languages = new Language();
		$options = $languages->get()->fetchPairs(Language::DATA_ID, Language::DATA_NAME);
		$form->addSelect(Users::DATA_LANGUAGE, Locales::get("users")->get("preferred_language"), $options);
		
		// Form process etc.
		if ($this->userFormType == "new") {
			$form->getComponent(Users::DATA_PASSWORD)
				->addRule(Form::FILLED, Locales::get("users")->get("password_not_filled"));
			$form->getComponent("pasword_check")
				->addRule(Form::FILLED, Locales::get("users")->get("password_check_not_filled"));
			$defaults = array(
				Users::DATA_LANGUAGE => Site::getInstance()->getLanguage()->id_language
			);
			$form->setDefaults($defaults);
			$form->addSubmit("userInfoSubmit", Locales::get("users")->get("register"));
			$form->onSubmit[] = array($this, "registerSubmitted");
		}
		else {
			if (Environment::getUser()->isAuthenticated()) {
				$identity = Environment::getUser()->getIdentity();
				$defaults = array(
					Users::DATA_LANGUAGE => $identity->id_language,
					Users::DATA_EMAIL => $identity->email,
					Users::DATA_SEX => $identity->sex,
					Users::DATA_YEAR_OF_BIRTH => $identity->birth_year
				);
				$form->setDefaults($defaults);
			}
			$form->addSubmit("userInfoSubmit", Locales::get()->get("edit"));
			$form->onSubmit[] = array($this, "editSubmitted");
		}

		return $form;
	}

	protected function createUsersDataGrid($name) {
		$dataGrid = $this->getSimpleDataGrid();

		$users = new Users();

		$dataGrid->bindDataTable($users->get());

		// setup columns
		$dataGrid->addColumn(Users::DATA_ID, "#");
		$dataGrid->addColumn(Users::DATA_NICKNAME, Locales::get("users")->get("nickname"))
			->addFilter();

		return $dataGrid;
	}

}