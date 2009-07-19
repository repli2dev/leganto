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

		if (!Environment::getUser()->isAuthenticated()) {
			$template->form = $this->getComponent("loginForm");
			$template->register = Locales::get("users")->get("register");
		}
		else {
			$template->user = Environment::getUser()->getIdentity();
		}

		$template->render();
	}


	public function handleLogout() {
		Environment::getUser()->signOut();
		$this->getPresenter()->redirect(":Frontend:Default:default");
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
						Locales::get("users")->get("user_not_found"),
						"error"
					);
					break;
				case AuthenticationException::INVALID_CREDENTIAL:
					$this->getPresenter()->flashMessage(
						Locales::get("users")->get("wrong_password"),
						"error"
					);
					break;
			}
			Debug::processException($e);
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	protected function createLoginForm($name) {
		$form = new AppForm($this,"loginForm");

		$form->addGroup(Locales::get("users")->get("login"))
			->setOption("container", Html::el("fieldset")->class("login"));

		$form->addText(IAuthenticator::USERNAME, Locales::get("users")->get("email") . ":")
			->addRule(Form::FILLED, Locales::get("users")->get("email_not_filled"));

		$form->addPassword(IAuthenticator::PASSWORD, Locales::get("users")->get("password") . ":")
			->addRule(Form::FILLED, Locales::get("users")->get("password_not_filled"));

		$form->addSubmit("loginSubmit", Locales::get("users")->get("login"));

		$form->onSubmit[] = array($this,"loginSubmitted");

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = "p";
		$renderer->wrappers['label']['container'] = NULL;
		$renderer->wrappers['control']['container'] = NULL;


		return $form;
	}
}