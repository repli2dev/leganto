<?php
/**
 * It looks after logging of the users
 *
 * @author Jan Papousek
 */
class UserComponent extends BaseControl
{

    public function render() {
		$template = $this->createTemplate();
		$template->setFile(TEMPLATES_DIR . '/UsersModule/components/user.phtml');
		$template->registerFilter(/*Nette\Templates\*/'CurlyBracketsFilter::invoke');

		if (!Environment::getUser()->isAuthenticated()) {
			$template->form = $this->getComponent("loginForm");
		}
		else {
			$template->user = Environment::getUser()->getIdentity();
			$template->logout = Locales::get("users")->get("logout");
		}

		$template->render();
	}


	public function handleLogout() {
		Environment::getUser()->signOut();
	}

	public function loginSubmitted(Form $form) {
		$values = $form->getValues();
		
		try {
			Environment::getUser()->authenticate(
				$values[IAuthenticator::USERNAME],
				$values[IAuthenticator::PASSWORD]
			);

		}
		catch (AuthenticationException $e) {
			switch ($e->getCode()) {
				case AuthenticationException::IDENTITY_NOT_FOUND:
					$this->getPresenter()->flashMessage(
						Locales::get("users")->get("identity_not_found"),
						"error"
					);
					break;
				case AuthenticationException::INVALID_CREDENTIAL:
					$this->getPresenter()->flashMessage(
						Locales::get("users")->get("invalid_credential"),
						"error"
					);
					break;
			}
			Debug::processException($e);
		}
	}

	protected function createLoginForm($name) {
		$form = new AppForm($this,"loginForm");

		$form->addText(IAuthenticator::USERNAME, Locales::get("users")->get("email") . ":")
			->addRule(Form::FILLED, Locales::get("users")->get("email_not_filled"));

		$form->addPassword(IAuthenticator::PASSWORD, Locales::get("users")->get("password") . ":")
			->addRule(Form::FILLED, Locales::get("users")->get("password_not_filled"));

		$form->addSubmit("loginSubmit", Locales::get("users")->get("login"));
		$form->onSubmit[] = array($this,"loginSubmitted");

		return $form;
	}
}