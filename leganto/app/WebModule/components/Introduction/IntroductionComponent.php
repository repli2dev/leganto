<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class IntroductionComponent extends BaseComponent {

	/**
	 * @persistent
	 * @var string
	 */
	public $state = "default";
	public $twitter;
	public $facebook;

	public function __construct(/* Nette\ */IComponentContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		// FIXME: toto je opravdu osklivy hack! Jak obejit to ze state jeste nebyl nastaven, ale pri vytvareni komponenty uz je pozde - vystup odchazi?!
		$newState = Environment::getHttpRequest()->getQuery("introduction-state");
		if ($newState == "twitter") {
			$this->twitter = new TwitterBridge;
			$this->twitter->doLoginWithAuthentication();
		} else
		if ($newState == "facebook") {
			$this->facebook = new FacebookBridge;
			$this->facebook->doLoginWithAuthentication();
		}
	}

	public function render() {
		switch ($this->state) {
			case "default":
				$template = $this->loadDefaultTemplate();
				break;
			case "login":
				$template = $this->loadLoginTemplate();
				break;
			case "signup":
				$template = $this->loadSignUpTemplate();
				break;
			case "facebook":
				$template = $this->loadFacebookTemplate();
				break;
			case "twitter":
				$template = $this->loadTwitterTemplate();
				break;
			case "forgotten":
				$template = $this->loadForgottenTemplate();
				break;
			case "renew":
				$template = $this->loadRenewTemplate();
				break;
		}
		$template->render();
	}

	public function loadDefaultTemplate() {
		$template = $this->getTemplate();
		$template->hint = Leganto::help()->getSelector()->findRandom(System::language());
		$template->state = "default";
		return $template;
	}

	public function loadLoginTemplate() {
		$template = $this->getTemplate();
		$template->state = "login";
		return $template;
	}

	public function loadSignUpTemplate() {
		$template = $this->getTemplate();
		$template->state = "signup";
		return $template;
	}

	public function loadFacebookTemplate() {
		$template = $this->getTemplate();
		$template->state = "facebook";
		$template->enabled = $this->facebook->isEnabled();
		return $template;
	}

	public function loadTwitterTemplate() {
		$template = $this->getTemplate();
		$template->state = "twitter";
		$template->enabled = $this->twitter->isEnabled();
		return $template;
	}

	public function loadForgottenTemplate() {
		$template = $this->getTemplate();
		$template->state = "forgotten";
		return $template;
	}

	public function loadRenewTemplate() {
		$template = $this->getTemplate();
		$template->state = "renew";
		return $template;
	}

	/* HANDLERS */

	public function handleChangeState($state) {
		switch ($state) {
			case "default":
			case "login":
			case "signup":
			case "facebook":
			case "twitter":
			case "forgotten":
			case "renew":
				if ($state == "facebook" && !$this->facebook->isEnabled()) {
					$this->getPresenter()->flashMessage(System::translate("Facebook functions are not accessible right now. Please try it later."), "error");
					$this->getPresenter()->redirect("Default:");
				} else
				if ($state == "twitter" && !$this->twitter->isEnabled()) {
					$this->getPresenter()->flashMessage(System::translate("Twitter functions are not accessible right now. Please try it later."), "error");
					$this->getPresenter()->redirect("Default:");
				} else
					$this->state = $state;
				break;
			default:
				throw new InvalidArgumentException("The state can be only default, login, facebook, twitter, signup, forgotten or renew");
		}
		$this->invalidateControl("introduction-block");
	}

	/**
	 * Create new account from twitter data (with filled name etc, but empty password and email)
	 */
	public function handleSignUpViaTwitter() {
		// Get data for registering profile + load session
		$data = $this->twitter->userInfo();

		// Prepare user entity
		$user = Leganto::users()->createEmpty();
		$user->role = "common";
		$user->idLanguage = System::domain()->idLanguage;
		$user->inserted = new DateTime();
		$user->nickname = $data->screen_name;

		// Commit
		$user = Leganto::users()->getInserter()->insert($user);
		if ($user != -1) {
			// Prepare user connection entity
			$connection = Leganto::connections()->createEmpty();
			$connection->user = $user;
			$connection->type = 'twitter';
			$connection->token = $this->twitter->getToken();

			// Commit
			Leganto::connections()->getInserter()->insert($connection);

			Environment::getUser()->authenticate(null, null, $this->twitter->getToken());
			System::log("SIGN UP VIA TWITTER");

			// Now it is safe to delete twitter data in session
			$this->twitter->destroyLoginData();
			$this->getPresenter()->redirect("Default:feed", true);
		} else {
			// Show error that same account (probably nick) exists
			$this->flashMessage(System::translate("An account with the same nickname is already registered."));
		}
	}

	/**
	 * Create new account from twitter data (with filled name etc, but empty password and email)
	 */
	public function handleSignUpViaFacebook() {
		// Get data for registering profile + load session
		$data = $this->facebook->userInfo();

		// Prepare user entity
		$user = Leganto::users()->createEmpty();
		$user->role = "common";
		$user->idLanguage = System::domain()->idLanguage;
		$user->inserted = new DateTime();
		$user->nickname = $data["username"];

		// Commit
		$user = Leganto::users()->getInserter()->insert($user);
		if ($user != -1) {
			// Prepare user connection entity
			$connection = Leganto::connections()->createEmpty();
			$connection->user = $user;
			$connection->type = 'facebook';
			$connection->token = $this->facebook->getToken();

			// Commit
			Leganto::connections()->getInserter()->insert($connection);

			Environment::getUser()->authenticate(null, null, $this->facebook->getToken());
			System::log("SIGN UP VIA FACEBOOK");

			// Now it is safe to delete facebook data in session
			$this->facebook->destroyLoginData();
			$this->getPresenter()->redirect("Default:feed", true);
		} else {
			// Show error that same account (probably nick) exists
			$this->flashMessage(System::translate("An account with the same nickname is already registered."));
		}
	}

	/* COMPONENTS - FACTORY */

	protected function createComponentTwitterForm($name) {
		$form = new BaseForm();
		if ($this->twitter->isLogged() == true) {
			// Continue with connecting accounts - show user the options -> form
			// login part of form
			$group = $form->addGroup();
			$form->addText("nickname", "Nickname")
				->addRule(Form::FILLED, "Please fill the nickname.");
			$form->addPassword("password", "Password")
				->addRule(Form::FILLED, "Please fill the password.");
			$form->addSubmit("submitted", "Log in");
			$form->onSubmit[] = array($this, "loginTwitterFormSubmitted");
		}
		return $form;
	}

	protected function createComponentForgottenForm($name) {
		$form = new BaseForm;
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Forgotten password");
		$form->addText("email", "E-mail")
			->addRule(Form::EMAIL, "Please fill the correct e-mail.")
			->addRule(Form::FILLED, "Please fill the e-mail.");
		$form->addSubmit("submitted", "Proceed");
		$form->onSubmit[] = array($this, "forgottenFormSubmitted");
		return $form;
	}

	protected function createComponentRenewForm($name) {
		$form = new BaseForm;
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Renew password");
		$form->addText("email", "E-mail")
			->addRule(Form::EMAIL, "Please fill the correct e-mail.")
			->addRule(Form::FILLED, "Please fill the e-mail.");
		$form->addText("hash", "Code")
			->addRule(Form::FILLED, "Please fill the correct code.");
		$form->addSubmit("submitted", "Finish");
		$form->onSubmit[] = array($this, "renewFormSubmitted");
		return $form;
	}

	protected function createComponentLoginForm($name) {
		$form = new BaseForm;
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Log in");
		$form->addText("nickname", "Nickname")
			->addRule(Form::FILLED, "Please fill the nickname.");
		$form->addPassword("password", "Password")
			->addRule(Form::FILLED, "Please fill the password.");
		$form->addSubmit("submitted", "Log in");
		$form->onSubmit[] = array($this, "loginFormSubmitted");
		return $form;
	}

	protected function createComponentSignUpForm($name) {
		// Create form skeleton
		$form = new BaseForm($this, $name);
		$form->addGroup("Sign Up");
		$form->getElementPrototype()->setId("sign");
		$form->addText("email", "E-mail")
			->addRule(Form::EMAIL, "Please fill the correct e-mail.")
			->addRule(Form::FILLED, "Please fill the e-mail.");
		$form->addText("nickname", "Nickname")
			->addRule(Form::FILLED, "Please fill the nickname.");
		$form->addPassword("password", "Password")
			->addRule(Form::FILLED, "Please fill the password.");
		$form->addPassword("password2", "Password again")
			->addRule(Form::FILLED, "Please fill the second password to check it.")
			->addConditionOn($form["password"], Form::FILLED)
			->addRule(Form::EQUAL, "Passwords have to match!", $form["password"]);
		$form->addSpamProtection();
		$form->addSubmit("submitted", "Register");
		$form->onSubmit[] = array($this, "signUpFormSubmitted");
		return $form;
	}

	protected function createComponentFacebookForm($name) {
		$form = new BaseForm();
		if ($this->facebook->isLogged() == true) {
			// Continue with connecting accounts - show user the options -> form
			// login part of form
			$group = $form->addGroup();
			$form->addText("nickname", "Nickname")
				->addRule(Form::FILLED, "Please fill the nickname.");
			$form->addPassword("password", "Password")
				->addRule(Form::FILLED, "Please fill the password.");
			$form->addSubmit("submitted", "Log in");
			$form->onSubmit[] = array($this, "loginFacebookFormSubmitted");
		}
		return $form;
	}

	/* FORM SIGNALS */

	public function forgottenFormSubmitted(Form $form) {
		$values = $form->getValues();

		// Look for user
		$user = Leganto::users()->getSelector()->findByEmail($values["email"]);
		if ($user != NULL) {
			$hash = Leganto::users()->getUpdater()->generateHashForNewPassword($user);
			// Prepare mail template
			$template = LegantoTemplate::loadTemplate(new Template());
			$template->setFile(WebModule::getModuleDir() . "/templates/mails/forgottenPassword.phtml");
			$template->hash = $hash;
			$template->url = Environment::getHttpRequest()->uri->getHostUri() . "" . $this->link("changeState!", "renew");
			// Send mail with new pass key
			$mail = new Mail();
			$mail->addTo($user->email);
			$mail->setFrom(Environment::getConfig("mail")->info, Environment::getConfig("mail")->name);
			$mail->setSubject(System::translate("Leganto: request for new password"));
			$mail->setBody($template);
			$mail->send();
			// Log action
			System::log("REQUESTED NEW PASSWORD FOR USER '".$user->getId()."'",$user->getId());
			// Presmerovat na renew
			$this->flashMessage(System::translate("The code was sent to an account e-mail address."));
			$this->state = "renew";
			$this->invalidateControl();
		} else {
			$form->addError("No user with this e-mail address exists. Please check for mistakes.");
		}
	}

	public function renewFormSubmitted(Form $form) {
		$values = $form->getValues();

		// Look for user
		$user = Leganto::users()->getSelector()->findByEmail($values["email"]);
		if ($user != NULL) {
			try {
				$password = Leganto::users()->getUpdater()->confirmNewPassword($user, $values["hash"]);
				// Prepare mail template
				$template = LegantoTemplate::loadTemplate(new Template());
				$template->setFile(WebModule::getModuleDir() . "/templates/mails/renewPassword.phtml");
				$template->nickname = $user->nickname;
				$template->password = $password;
				// Send mail with new pass key
				$mail = new Mail();
				$mail->addTo($user->email);
				$mail->setFrom(Environment::getConfig("mail")->info, Environment::getConfig("mail")->name);
				$mail->setSubject(System::translate("Leganto: new password"));
				$mail->setBody($template);
				$mail->send();
				// Log action
				System::log("SETTNG NEW PASSWORD FOR USER '".$user->getId()."'",$user->getId());
				// Presmerovat na login
				$this->flashMessage(System::translate("Your new password was sent to an account e-mail address."));
				$this->state = "login";
				$this->invalidateControl();
			} catch (InvalidStateException $e) {
				switch ($e->getCode()) {
					case UserUpdater::ERROR_OLD_HASH:
						$form->addError("The code is too old. You can try a new request.");
						break;
					case UserUpdater::ERROR_WRONG_HASH:
						$form->addError("The code is wrong. Have you typed it correctly?");
						break;
				}
				// Log action
				System::log("INVALID SETTING OF NEW PASSWORD FOR USER '".$user->getId()."'",$user->getId());
			}
		} else {
			$form->addError("No user with this e-mail address exists. Please check for mistakes.");
		}
	}

	/*
	 * This handler take care of choosing screen of facebook login
	 */

	public function loginFacebookFormSubmitted(Form $form) {
		$values = $form->getValues();
		try {
			Environment::getUser()->authenticate($values['nickname'], $values['password']);
		} catch (AuthenticationException $e) {
			switch ($e->getCode()) {
				case IAuthenticator::IDENTITY_NOT_FOUND:
					$form->addError("User not found");
					break;
				case IAuthenticator::INVALID_CREDENTIAL:
					$form->addError("The password is wrong");
					break;
			}
		}
		// If user was successfully logged and there were found data in session (namespace facebook) -> add connection
		$user = Environment::getUser()->getIdentity();
		if ($user != NULL) {
			$this->twitter = new FacebookBridge;
			$facebookToken = $this->twitter->getToken();
			if (!empty($facebookToken)) {
				$exists = Leganto::connections()->getSelector()->exists($user->id, 'facebook');
				if (!$exists) {
					// Prepare user connection entity
					$connection = Leganto::connections()->createEmpty();
					$connection->user = $user->id;
					$connection->type = 'facebook';
					$connection->token = $facebookToken;

					// Commit
					Leganto::connections()->getInserter()->insert($connection);
					System::log("INSERT CONNECCTION TO FACEBOOK '" . $connection->getId() . "'");

					// Now it is safe to delete facebook data in session
					$this->facebook->destroyLoginData();
				} else {
					// This twitter id is already connected to some account
					$form->addError("This Facebook account is already connected to an account.");
				}
			}
		}
	}

	/*
	 * This handler take care of choosing screen of twitter login
	 */

	public function loginTwitterFormSubmitted(Form $form) {
		$values = $form->getValues();
		try {
			Environment::getUser()->authenticate($values['nickname'], $values['password']);
		} catch (AuthenticationException $e) {
			switch ($e->getCode()) {
				case IAuthenticator::IDENTITY_NOT_FOUND:
					$form->addError("User not found");
					break;
				case IAuthenticator::INVALID_CREDENTIAL:
					$form->addError("The password is wrong");
					break;
			}
		}
		// If user was successfully logged and there were found data in session (namespace twitter) -> add connection
		$user = Environment::getUser()->getIdentity();
		if ($user != NULL) {
			$this->twitter = new TwitterBridge;
			$twitterToken = $this->twitter->getToken();
			if (!empty($twitterToken)) {
				$exists = Leganto::connections()->getSelector()->exists($user->id, 'twitter');
				if (!$exists) {
					// Prepare user connection entity
					$connection = Leganto::connections()->createEmpty();
					$connection->user = $user->id;
					$connection->type = 'twitter';
					$connection->token = $twitterToken;

					// Commit
					Leganto::connections()->getInserter()->insert($connection);
					System::log("INSERT CONNECTION TO TWITTER '" . $connection->getId() . "'");

					// Now it is safe to delete twitter data in session
					$this->twitter->destroyLoginData();
				} else {
					// This twitter id is already connected to some account
					$form->addError("This Twitter account is already connected to an account.");
				}
			}
		}
	}

	public function loginFormSubmitted(Form $form) {
		$values = $form->getValues();
		try {
			Environment::getUser()->authenticate($values['nickname'], $values['password']);
			System::log("LOGIN");
		} catch (AuthenticationException $e) {
			switch ($e->getCode()) {
				case IAuthenticator::IDENTITY_NOT_FOUND:
					$form->addError("User not found");
					break;
				case IAuthenticator::INVALID_CREDENTIAL:
					$form->addError("The password is wrong");
					break;
			}
		}
	}

	public function signUpFormSubmitted(Form $form) {
		$values = $form->getValues();

		// Create entity and fill it with user data
		$user = Leganto::users()->createEmpty();
		$user->nickname = $values["nickname"];
		$user->email = $values["email"];
		$user->password = UserAuthenticator::passwordHash($values["password"]);

		// Add system data
		$user->role = "common";
		$user->idLanguage = System::domain()->idLanguage;
		$user->inserted = new DateTime();

		// Commit & postSignUp
		// FIXME: hack kvuli tomu ze nick neni v databazi nastaven jako unique (-> je mozne nekomu ukradnout identitu)
		$nickExists = dibi::dataSource("SELECT * FROM [user] WHERE [nick] = %s", $values["nickname"])->count();
		if ($nickExists == 0) {
			$user = Leganto::users()->getInserter()->insert($user);
		} else {
			$user = -1;
			$form->addError("An account with the same nickname or e-mail is already registered.");
		}
		if ($user != -1) {
			$user = Leganto::users()->getSelector()->find($user);

			$template = LegantoTemplate::loadTemplate(new Template());
			$template->setFile(WebModule::getModuleDir() . "/templates/mails/signUp.phtml");
			$template->nickname = $values["nickname"];
			$template->password = $values["password"];

			$mail = new Mail();
			$mail->addTo($user->email);
			$mail->setFrom(Environment::getConfig("mail")->info, Environment::getConfig("mail")->name);
			$mail->setSubject(System::translate("Leganto: thanks for your registration"));
			$mail->setBody($template);
			$mail->send();

			// Authentiticate at last
			try {
				Environment::getUser()->authenticate($values['nickname'], $values['password']);
				System::log("INSERT USER");
			} catch (AuthenticationException $e) {
				switch ($e->getCode()) {
					case IAuthenticator::IDENTITY_NOT_FOUND:
						$form->addError("User not found");
						break;
					case IAuthenticator::INVALID_CREDENTIAL:
						$form->addError("The password is wrong");
						break;
				}
			}
			$this->getPresenter()->flashMessage(System::translate("Thanks for your registration."), "success");
			$this->getPresenter()->redirect("Default:feed", true);
		} else {
			$form->addError("An account with the same nickname or e-mail is already registered.");
		}
	}
	public function handleNextHint() {
		$this->invalidateControl("introduction-block");
	}

}

