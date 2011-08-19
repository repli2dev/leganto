<?php

/**
 * Welcome & Login & Register component
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Nette\ComponentModel\IContainer,
    Leganto\DB\Factory,
    InvalidArgumentException,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Nette\Security\AuthenticationException,
    Nette\Security\IAuthorizator,
    Nette\Security\IAuthenticator,
    Leganto\Templating\Template as LegantoTemplate,
    Nette\Templating\FileTemplate,
    Leganto\Templating\Helpers,
    Leganto\DB\User\Authenticator,
    Nette\DateTime,
    Leganto\DB\Connection\TwitterBridge,
    Leganto\DB\Connection\FacebookBridge;

class Introduction extends BaseComponent {

	/**
	 * @persistent
	 * @var string
	 */
	public $state = "default";
	public $twitter;
	public $facebook;

	public function __construct(IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		// FIXME: toto je opravdu osklivy hack! Jak obejit to ze state jeste nebyl nastaven, ale pri vytvareni komponenty uz je pozde - vystup odchazi?!
		$newState = $this->getContext()->httpRequest->getQuery("introduction-state");
		if ($newState == "twitter") {
			$this->twitter = new TwitterBridge($this->getContext());
			$this->twitter->doLoginWithAuthentication();
		} else
		if ($newState == "facebook") {
			$this->facebook = new FacebookBridge($this->getContext());
			$this->facebook->doLoginWithAuthentication();
		}
	}

	public function startUp() {
		parent::startUp();
		$this->getTemplate()->language = $this->getContext()->getService("environment")->language();
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
		$template->hint = Factory::help()->getSelector()->findRandom($this->getContext()->getService("environment")->language());
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
					$this->getPresenter()->flashMessage($this->translate("Facebook functions are not accessible right now. Please try it later."), "error");
					$this->getPresenter()->redirect("Default:");
				} else
				if ($state == "twitter" && !$this->twitter->isEnabled()) {
					$this->getPresenter()->flashMessage($this->translate("Twitter functions are not accessible right now. Please try it later."), "error");
					$this->getPresenter()->redirect("Default:");
				} else
					$this->state = $state;
				break;
			default:
				throw new InvalidArgumentException("The state can be only default, login, facebook, twitter, signup, forgotten or renew");
		}
		$this->invalidateControl("introductionBlock");
	}

	/**
	 * Create new account from twitter data (with filled name etc, but empty password and email)
	 */
	public function handleSignUpViaTwitter() {
		// Get data for registering profile + load session
		$data = $this->twitter->userInfo();

		// Prepare user entity
		$user = Factory::user()->createEmpty();
		$user->role = "common";
		$user->idLanguage = $this->getContext()->getService("environment")->domain()->idLanguage;
		$user->inserted = new DateTime();
		$user->nickname = $data->screen_name;

		// Commit
		$user = Factory::user()->getInserter()->insert($user);
		if ($user != -1) {
			// Prepare user connection entity
			$connection = Factory::connection()->createEmpty();
			$connection->user = $user;
			$connection->type = 'twitter';
			$connection->token = $this->twitter->getToken();
			$connection->secret = $this->twitter->getSecret();
			$connection->inserted = new DateTime();

			// Commit
			Factory::connection()->getInserter()->insert($connection);

			$this->getUser()->login(null, null, $this->twitter->getToken());
			$this->getContext()->getService("logger")->log("SIGN UP VIA TWITTER");

			// Now it is safe to delete twitter data in session
			$this->twitter->destroyLoginData();
			$this->getPresenter()->redirect("Default:feed", true);
		} else {
			// Show error that same account (probably nick) exists
			$this->flashMessage($this->translate("An account with the same nickname is already registered."));
		}
	}

	/**
	 * Create new account from twitter data (with filled name etc, but empty password and email)
	 */
	public function handleSignUpViaFacebook() {
		// Get data for registering profile + load session
		$data = $this->facebook->userInfo();

		// Prepare user entity
		$user = Factory::users()->createEmpty();
		$user->role = "common";
		$user->idLanguage = System::domain()->idLanguage;
		$user->inserted = new DateTime();
		$user->nickname = $data["username"];

		// Commit
		$user = Factory::users()->getInserter()->insert($user);
		if ($user != -1) {
			// Prepare user connection entity
			$connection = Factory::connections()->createEmpty();
			$connection->user = $user;
			$connection->type = 'facebook';
			$connection->token = $this->facebook->getToken();
			$connection->inserted = new DateTime();

			// Commit
			Factory::connections()->getInserter()->insert($connection);

			Environment::getUser()->authenticate(null, null, $this->facebook->getToken());
			System::log("SIGN UP VIA FACEBOOK");

			// Now it is safe to delete facebook data in session
			$this->facebook->destroyLoginData();
			$this->getPresenter()->redirect("Default:feed", true);
		} else {
			// Show error that same account (probably nick) exists
			$this->flashMessage($this->translate("An account with the same nickname is already registered."));
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
		$form = new BaseForm($this, $name);
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Forgotten password");
		$form->addText("email", "E-mail")
			->addRule(Form::EMAIL, "Please fill the correct e-mail.")
			->addRule(Form::FILLED, "Please fill the e-mail.");
		$form->addSubmit("submitted", "Proceed");
		$form->onSuccess[] = array($this, "forgottenFormSubmitted");
		return $form;
	}

	protected function createComponentRenewForm($name) {
		$form = new BaseForm($this, $name);
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Renew password");
		$form->addText("email", "E-mail")
			->addRule(Form::EMAIL, "Please fill the correct e-mail.")
			->addRule(Form::FILLED, "Please fill the e-mail.");
		$form->addText("hash", "Code")
			->addRule(Form::FILLED, "Please fill the correct code.");
		$form->addSubmit("submitted", "Finish");
		$form->onSuccess[] = array($this, "renewFormSubmitted");
		return $form;
	}

	protected function createComponentLoginForm($name) {
		$form = new BaseForm($this, $name);
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Log in");
		$form->addText("nickname", "Nickname")
			->setRequired("Please fill the nickname.");
		$form->addPassword("password", "Password")
			->setRequired("Please fill the password.");
		$form->addSubmit("submitted", "Log in");
		$form->onSuccess[] = array($this, "loginFormSubmitted");
		return $form;
	}

	protected function createComponentSignUpForm($name) {
		// Enable text captcha (the only usage)
		\Nette\Forms\Container::extensionMethod('addTextCaptcha', array('\TextCaptcha\TextCaptcha', 'addTextCaptcha'));
		\TextCaptcha\TextCaptcha::setSession($this->getContext()->getService("session"));
		\TextCaptcha\TextCaptcha::setBackend(new \TextCaptcha\DatabaseBackend($this->getContext()->getService("database")));
		\TextCaptcha\TextCaptcha::setLanguage($this->getContext()->getService("environment")->domain()->idLanguage);
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
		$form->addTextCaptcha();
		$form->addSubmit("submitted", "Register");
		$form->onSuccess[] = array($this, "signUpFormSubmitted");
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
		$user = Factory::user()->getSelector()->findByEmail($values["email"]);
		if ($user != NULL) {
			$hash = Factory::user()->getUpdater()->generateHashForNewPassword($user);
			// Prepare mail template
			$template = LegantoTemplate::loadTemplate(new FileTemplate());
			$template->setFile(__DIR__ . "/mails/ForgottenPassword.latte");
			$template->hash = $hash;
			$template->url = $this->getContext()->httpRequest->uri->getHostUri() . "" . $this->link("changeState!", "renew");
			// Send mail with new pass key
			$mail = Helpers::getMailPrototype();
			$mail->addTo($user->email);
			$mail->setFrom($this->getContext()->params["mail"]["info"], $this->getContext()->params["mail"]["name"]);
			$mail->setSubject($this->translate("Leganto: request for new password"));
			$mail->setBody($template);
			$mail->send();
			// Log action
			$this->getContext()->getService("logger")->log("REQUESTED NEW PASSWORD FOR USER '" . $user->getId() . "'", $user->getId());
			// Presmerovat na renew
			$this->flashMessage($this->translate("The code was sent to an account e-mail address."));
			$this->state = "renew";
			$this->invalidateControl();
		} else {
			$form->addError("No user with this e-mail address exists. Please check for mistakes.");
		}
	}

	public function renewFormSubmitted(Form $form) {
		$values = $form->getValues();

		// Look for user
		$user = Factory::user()->getSelector()->findByEmail($values["email"]);
		if ($user != NULL) {
			$logger = $this->getContext()->getService("logger");
			try {
				$password = Factory::user()->getUpdater()->confirmNewPassword($user, $values["hash"]);
				// Prepare mail template
				$template = LegantoTemplate::loadTemplate(new FileTemplate());
				$template->setFile(__DIR__ . "/mails/RenewPassword.latte");
				$template->nickname = $user->nickname;
				$template->password = $password;
				// Send mail with new pass key
				$mail = Helpers::getMailPrototype();
				$mail->addTo($user->email);
				$mail->setFrom($this->getContext()->params["mail"]["info"], $this->getContext()->params["mail"]["name"]);
				$mail->setSubject($this->translate("Leganto: new password"));
				$mail->setBody($template);
				$mail->send();
				// Log action
				$logger->log("SETTNG NEW PASSWORD FOR USER '" . $user->getId() . "'", $user->getId());
				// Presmerovat na login
				$this->flashMessage($this->translate("Your new password was sent to an account e-mail address."));
				$this->state = "login";
				$this->invalidateControl();
			} catch (InvalidStateException $e) {
				switch ($e->getCode()) {
					case \Leganto\DB\User\Updater::ERROR_OLD_HASH:
						$form->addError("The code is too old. You can try a new request.");
						break;
					case \Leganto\DB\User\UserUpdater::ERROR_WRONG_HASH:
						$form->addError("The code is wrong. Have you typed it correctly?");
						break;
				}
				// Log action
				$logger->log("INVALID SETTING OF NEW PASSWORD FOR USER '" . $user->getId() . "'", $user->getId());
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
			$this->getUser()->login($values['nickname'], $values['password']);
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
		$user = $this->getUser()->getIdentity();
		if ($user != NULL) {
			$this->twitter = new FacebookBridge;
			$facebookToken = $this->twitter->getToken();
			if (!empty($facebookToken)) {
				$exists = Factory::connections()->getSelector()->exists($user->id, 'facebook');
				if (!$exists) {
					// Prepare user connection entity
					$connection = Factory::connections()->createEmpty();
					$connection->user = $user->id;
					$connection->type = 'facebook';
					$connection->token = $facebookToken;
					$connection->inserted = new DateTime();

					// Commit
					Factory::connections()->getInserter()->insert($connection);
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
			$this->getUser()->login($values['nickname'], $values['password']);
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
		$user = $this->getUser()->getIdentity();
		if ($this->getUser()->isLoggedIn()) {
			$this->twitter = new TwitterBridge($this->getContext());
			$twitterToken = $this->twitter->getToken();
			if (!empty($twitterToken)) {
				$exists = Factory::connection()->getSelector()->exists($user->id, 'twitter');
				if (!$exists) {
					// Prepare user connection entity
					$connection = Factory::connection()->createEmpty();
					$connection->user = $user->id;
					$connection->type = 'twitter';
					$connection->token = $twitterToken;
					$connection->secret = $this->twitter->getSecret();
					$connection->inserted = new DateTime();

					// Commit
					Factory::connection()->getInserter()->insert($connection);
					$this->getContext()->getService("logger")->log("INSERT CONNECTION TO TWITTER '" . $connection->getId() . "'");

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
			$this->getUser()->login($values['nickname'], $values['password']);
			$this->getContext()->getService("logger")->log("LOGIN");
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
		$user = Factory::user()->createEmpty();
		$user->nickname = $values["nickname"];
		$user->email = $values["email"];
		$user->password = Authenticator::passwordHash($values["password"]);

		// Add system data
		$user->role = "common";
		$user->idLanguage = $this->getContext()->getService("environment")->domain()->idLanguage;
		$user->inserted = new DateTime();

		// Commit & postSignUp
		// FIXME: hack kvuli tomu ze nick neni v databazi nastaven jako unique (-> je mozne nekomu ukradnout identitu)
		$nickExists = $this->getContext()->getService("database")->dataSource("SELECT * FROM [user] WHERE [nick] = %s", $values["nickname"])->count();
		if ($nickExists == 0) {
			$user = Factory::user()->getInserter()->insert($user);
		} else {
			$user = -1;
			$form->addError("An account with the same nickname or e-mail is already registered.");
		}
		if ($user != -1) {
			$user = Factory::user()->getSelector()->find($user);

			$template = LegantoTemplate::loadTemplate(new FileTemplate());
			$template->setFile(__DIR__ . "/mails/SignUp.latte");
			$template->nickname = $values["nickname"];
			$template->password = $values["password"];

			$mail = Helpers::getMailPrototype();
			$mail->addTo($user->email);
			$mail->setFrom($this->getContext()->params["mail"]["info"], $this->getContext()->params["mail"]["name"]);
			$mail->setSubject($this->translate("Leganto: thanks for your registration"));
			$mail->setBody($template);
			$mail->send();

			// Authentiticate at last
			try {
				$this->getUser()->login($values['nickname'], $values['password']);
				$this->getContext()->getService("logger")->log("INSERT USER");
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
			$this->getPresenter()->flashMessage($this->translate("Thanks for your registration."), "success");
			$this->getPresenter()->redirect("Default:feed", true);
		} else {
			$form->addError("An account with the same nickname or e-mail is already registered.");
		}
	}

	public function handleNextHint() {
		$this->invalidateControl("introduction-block");
	}

}

