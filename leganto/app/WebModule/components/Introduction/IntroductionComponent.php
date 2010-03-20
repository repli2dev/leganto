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

	public function  __construct() {
		parent::__construct();

		$newState = Environment::getHttpRequest()->getQuery("introduction-state"); // FIXME: toto je opravdu osklivy hack! Jak obejit to ze state jeste nebyl nastaven, ale pri vytvareni komponenty uz je pozde - vystup odchazi?!
		if($newState == "twitter"){
			$this->twitter = new Twitter;
			$this->twitter->doLogin();
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
		return $template;
	}

	public function loadTwitterTemplate() {
		$template			= $this->getTemplate();
		$template->state	= "twitter";
		$template->twitterStatus = $this->twitter->isLogged();
		return $template;
	}

	/** PROTECTED **/

	protected function createComponentLoginForm($name) {
		$loginForm = new BaseForm;
		$loginForm->getElementPrototype()->setId("sign");
		$loginForm->addGroup("Log in");
		$loginForm->addText("nickname", "Nickname")
			->addRule(Form::FILLED,"Please fill the nickname.");
		$loginForm->addPassword("password", "Password")
			->addRule(Form::FILLED,"Please fill the password.");
		$loginForm->addSubmit("submitted", "Log in");
		$loginForm->onSubmit[] = array($this, "loginFormSubmitted");
		return $loginForm;
	}

	protected function createComponentSignUpForm($name) {
		// Create form skeleton
		$signUpForm = new BaseForm;
		$signUpForm->addGroup("Sign Up");
		$signUpForm->getElementPrototype()->setId("sign");
		// Create user entity and set defaults (for form building)
		$user = Leganto::users()->createEmpty();
		$user->role = "common";
		$user->idLanguage = System::domain()->idLanguage;
		$user->inserted = new DibiVariable("now()", "sql");
		// Add post action (send mail to user)
		$user->addOnPersistListener(new CallbackListener(array($this, "postSignUp")));
		// Build a form
		$builder = new SimpleFormBuilder($user, $signUpForm);
		$builder->disable("sex");
		$builder->disable("birth_year");
		$builder->disable("id_language");
		$form = $builder->buildForm();
		// Add remain items
		$form->addPassword("password2","Password again");
		$form->addSubmit("submitSignUp", "Register");
		$form->onSubmit[] = array($builder, "onSubmit");
		return $form;
	}

	protected function createComponentTwitterForm($name) {
		//$content = Html::el("div id=sign");
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
		} else {
			$form->addError(System::translate("Twitter functions are not accessible right now. Please try it later."));
		}
		return $form;
	}

	protected function createComponentFacebookForm($name) {
		// Procedure
		// Try look up, if user is already logged on facebook
		//    Logged -> continue
		//    Not logged -> jump to facebook

		// Try to find user ID from facebook in table user_connections
		//    Found -> good, automatic login
		//    Not found -> Temporary login, show options
		//        New account -> create dummy account here -> login
		//        Already have account -> link accounts and login

		$form = new BaseForm();
		$form->getElementPrototype()->setId("sign");

		if(Environment::getConfig("facebook")->enable) { // check if facebook is enabled
			$fbGate = new Facebook(Environment::getConfig("facebook")->apiKey,Environment::getConfig("facebook")->secret,NULL,TRUE);
			Debug::dump($fbGate);
			$fbUser = $fbGate->require_login();
			Debug::dump($fbUser);

			$form->addText("test","Test");
		} else {
			$form->addError(System::translate("Facebook functions have been temporary disabled. Please try it later."));
		}
		return $form;
	}

	public function postSignUp(EntityPersistedEvent $event) {
		$user = $event->getEntity();
		$template = LegantoTemplate::loadTemplate(new Template());
		$template->setFile(WebModule::getModuleDir() . "/templates/mails/signUp.phtml");

		$mail = new Mail();
		$mail->addTo($user->email);
		$mail->setFrom(Environment::getConfig("mail")->info, Environment::getConfig("mail")->name);
		$mail->setSubject(System::translate("Thanks for your registration."));
		$mail->setBody($template);
		$mail->send();

		$this->getPresenter()->flashMessage(System::translate("Thanks for your registration."));
		$this->getPresenter()->redirect("this");
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
			$this->twitter = new Twitter;
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
					$form->addError(System::translate("This twitter account is already connected to an account."));
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
				$this->state = $state;
				break;
			default:
				throw new InvalidArgumentException("The state can be only default, login, facebook, twitter or signup");
		}
		$this->invalidateControl("introduction-block");
	}

	/**
	 * Create new account from twitter data (with filled name etc, but empty password)
	 */
	public function handleSignUpViaTwitter() {
		// Get data for registering profile + load session
		$data = $this->twitter->userInfo();

		// Prepare user entity
		$user = Leganto::users()->createEmpty();
		$user->role = "common";
		// FIXME: pouziva se id_role? Proc je id_role a role - zbytecna duplikace
		$user->idLanguage = System::domain()->idLanguage;
		$user->inserted = new DibiVariable("now()", "sql");
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
}

