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
 *
 */

class IntroductionComponent extends BaseComponent {
	/**
	 * @persistent
	 * @var string
	 */
	public $state = "default";

	public $twitter;

	public $facebook;

	public function  __construct() {
		parent::__construct();

		// FIXME: toto je opravdu osklivy hack! Jak obejit to ze state jeste nebyl nastaven, ale pri vytvareni komponenty uz je pozde - vystup odchazi?!
		$newState = Environment::getHttpRequest()->getQuery("introduction-state"); 
		if($newState == "twitter") {
			$this->twitter = new TwitterBridge;
			$this->twitter->doLogin();
		} else
		if($newState == "facebook") {
			$this->facebook = new FacebookBridge;
			$this->facebook->doLogin();
		}
	}

	public function render() {
		switch($this->state) {
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
			case "forgot":
				$template = $this->loadForgotTemplate();
				break;
			case "renew":
				$template = $this->loadRenewTemplate();
				break;
		}
		$template->render();
	}

	public function loadDefaultTemplate() {
		$template			= $this->getTemplate();
		$template->hint		= Leganto::help()->getSelector()->findRandomByCategory();
		$template->state	= "default";
		return $template;
	}

	public function loadLoginTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "login";
		return $template;
	}

	public function loadSignUpTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "signup";
		return $template;
	}

	public function loadFacebookTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "facebook";
		$template->enabled = $this->facebook->isEnabled();
		return $template;
	}

	public function loadTwitterTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "twitter";
		$template->enabled = $this->twitter->isEnabled();
		return $template;
	}

	public function loadForgotTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "forgot";
		return $template;
	}
	public function loadRenewTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "renew";
		return $template;
	}

	/** PROTECTED **/

	protected function createComponentLoginForm($name) {
		$form = new BaseForm;
		$form->getElementPrototype()->setId("sign");
		$form->addGroup("Log in");
		$form->addText("nickname", "Nickname")
			->addRule(Form::FILLED,"Please fill the nickname.");
		$form->addPassword("password", "Password")
			->addRule(Form::FILLED,"Please fill the password.");
		$form->addSubmit("submitted", "Log in");
		$form->onSubmit[] = array($this, "loginFormSubmitted");
		return $form;
	}

	protected function createComponentSignUpForm($name) {
		// Create form skeleton
		$form = new BaseForm;
		$form->addGroup("Sign Up");
		$form->getElementPrototype()->setId("sign");
		$form->addText("email", "Email")
			->addRule(Form::EMAIL,"Please fill correct email.")
			->addRule(Form::FILLED,"Please fill the email.");
		$form->addText("nickname", "Nickname")
			->addRule(Form::FILLED,"Please fill the nickname.");
		$form->addPassword("password", "Password")
			->addRule(Form::FILLED,"Please fill the password.");
		$form->addPassword("password2", "Password again")
			->addRule(Form::FILLED,"Please fill the second password for check.")
			->addConditionOn($form["password"], Form::FILLED)
				->addRule(Form::EQUAL, "Passwords have to match!", $form["password"]);
		$form->addSubmit("submitted", "Register");
		$form->onSubmit[] = array($this, "signUpFormSubmitted");
		return $form;
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
		$nickExists = dibi::dataSource("SELECT * FROM [user] WHERE [nick] = %s",$values["nickname"])->count();
		if($nickExists == 0) {
			$user = Leganto::users()->getInserter()->insert($user);
		} else {
			$user = -1;
			$form->addError("Account with same nickname or email is already registered.");
		}
		if($user != -1) {
			$user = Leganto::users()->getSelector()->find($user);

			$template = LegantoTemplate::loadTemplate(new Template());
			$template->setFile(WebModule::getModuleDir() . "/templates/mails/signUp.phtml");
			$template->nickname = $values["nickname"];
			$template->password = $values["password"];

			$mail = new Mail();
			$mail->addTo($user->email);
			$mail->setFrom(Environment::getConfig("mail")->info, Environment::getConfig("mail")->name);
			$mail->setSubject(System::translate("Leganto: Thanks for your registration."));
			$mail->setBody($template);
			$mail->send();

			// Authentiticate at last
			try {
				Environment::getUser()->authenticate($values['nickname'],$values['password']);
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
			$this->getPresenter()->flashMessage(System::translate("Thanks for your registration."));
			$this->getPresenter()->redirect("this");
		} else {
			$form->addError("Account with same nickname or email is already registered.");
		}
	}

	protected function createComponentTwitterForm($name) {
		$form = new BaseForm();
		if($this->twitter->isLogged() == true){
			// Continue with connecting accounts - show user the options -> form
			// login part of form
			$group = $form->addGroup();
			$form->addText("nickname", "Nickname")
				->addRule(Form::FILLED,"Please fill the nickname.");
			$form->addPassword("password", "Password")
				->addRule(Form::FILLED,"Please fill the password.");
			$form->addSubmit("submitted", "Log in");
			$form->onSubmit[] = array($this, "loginTwitterFormSubmitted");
		}
		return $form;
	}

	protected function createComponentFacebookForm($name) {
		$form = new BaseForm();
		if($this->facebook->isLogged() == true){
			// Continue with connecting accounts - show user the options -> form
			// login part of form
			$group = $form->addGroup();
			$form->addText("nickname", "Nickname")
				->addRule(Form::FILLED,"Please fill the nickname.");
			$form->addPassword("password", "Password")
				->addRule(Form::FILLED,"Please fill the password.");
			$form->addSubmit("submitted", "Log in");
			$form->onSubmit[] = array($this, "loginFacebookFormSubmitted");
		}
		return $form;
	}

	/** HANDLERS **/
	public function loginFormSubmitted(Form $form) {
		$values = $form->getValues();
		try {
			Environment::getUser()->authenticate($values['nickname'],$values['password']);
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

	/*
	 * This handler take care of choosing screen of twitter login
	 */
	public function loginTwitterFormSubmitted(Form $form) {
		$values = $form->getValues();
		try {
			Environment::getUser()->authenticate($values['nickname'],$values['password']);
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
		if($user != NULL){
			$this->twitter = new TwitterBridge;
			$twitterToken = $this->twitter->getToken();
			if(!empty($twitterToken)){
				$exists = Leganto::connections()->getSelector()->exists($user->id,'twitter');
				if(!$exists){
					// Prepare user connection entity
					$connection = Leganto::connections()->createEmpty();
					$connection->user = $user->id;
					$connection->type = 'twitter';
					$connection->token = $twitterToken;

					// Commit
					Leganto::connections()->getInserter()->insert($connection);

					// Now it is safe to delete twitter data in session
					$this->twitter->destroyLoginData();
				} else {
					// This twitter id is already connected to some account
					$form->addError("This twitter account is already connected to an account.");
				}
			}
		}
		
	}

	/*
	 * This handler take care of choosing screen of twitter login
	 */
	public function loginFacebookFormSubmitted(Form $form) {
		$values = $form->getValues();
		try {
			Environment::getUser()->authenticate($values['nickname'],$values['password']);
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
		if($user != NULL){
			$this->twitter = new FacebookBridge;
			$facebookToken = $this->twitter->getToken();
			if(!empty($facebookToken)){
				$exists = Leganto::connections()->getSelector()->exists($user->id,'facebook');
				if(!$exists){
					// Prepare user connection entity
					$connection = Leganto::connections()->createEmpty();
					$connection->user = $user->id;
					$connection->type = 'facebook';
					$connection->token = $facebookToken;

					// Commit
					Leganto::connections()->getInserter()->insert($connection);

					// Now it is safe to delete facebook data in session
					$this->facebook->destroyLoginData();
				} else {
					// This twitter id is already connected to some account
					$form->addError("This facebook account is already connected to an account.");
				}
			}
		}

	}

	public function handleChangeState($state) {
		switch($state) {
			case "default":
			case "login":
			case "signup":
			case "facebook":
			case "twitter":
			case "forgot":
			case "renew":
				$this->state = $state;
				break;
			default:
				throw new InvalidArgumentException("The state can be only default, login, facebook, twitter, signup, forgot or renew");
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
		if($user != -1){
			// Prepare user connection entity
			$connection = Leganto::connections()->createEmpty();
			$connection->user = $user;
			$connection->type = 'twitter';
			$connection->token = $this->twitter->getToken();

			// Commit
			Leganto::connections()->getInserter()->insert($connection);

			Environment::getUser()->authenticate(null,null,$this->twitter->getToken());

			// Now it is safe to delete twitter data in session
			$this->twitter->destroyLoginData();
			
		} else {
			// Show error that same account (probably nick) exists
			$this->flashMessage(System::translate("Account with same nickname is already registered."));
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
		if($user != -1){
			// Prepare user connection entity
			$connection = Leganto::connections()->createEmpty();
			$connection->user = $user;
			$connection->type = 'facebook';
			$connection->token = $this->facebook->getToken();

			// Commit
			Leganto::connections()->getInserter()->insert($connection);

			Environment::getUser()->authenticate(null,null,$this->facebook->getToken());

			// Now it is safe to delete facebook data in session
			$this->facebook->destroyLoginData();

		} else {
			// Show error that same account (probably nick) exists
			$this->flashMessage(System::translate("Account with same nickname is already registered."));
		}

	}
}

