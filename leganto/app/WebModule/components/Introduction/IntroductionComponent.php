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

	public $twitterStatus = false;

	public function  __construct() {
		parent::__construct();

		$newState = Environment::getHttpRequest()->getQuery("introduction-state"); // FIXME: toto je opravdu osklivy hack! Jak obejit to ze state jeste nebyl nastaven, ale pri vytvareni komponenty uz je pozde - vystup odchazi?!
		if($newState == "twitter"){
			$this->twitter = new Twitter;
			// Try if session is filled with right data (because then it ok to skip authentification)
			$session = Environment::getSession("twitter"); // Open session namespace for twitter data
			if(isset($session->auth['oauth_token']) && $session->auth['oauth_token_secret']){
				$this->twitterStatus = true;
			} else
			if($this->twitter->authentification()){ // Authentification was successful
				$this->twitterStatus = true;
			} else {
				$this->twitterStatus = false;
			}

		}
		// If twitterStatus is true then try to look up for existing connection -> if it is found then user is logged automatically
		if($this->twitterStatus == true){
			try {
				Environment::getUser()->authenticate(null,null,$session->auth['oauth_token']);
			} catch (AuthenticationException $e) {
				// Silent error - output is not desired - connection was not found -> show message to let user choose what to do
			}
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
		$template->twitterStatus = $this->twitterStatus;
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
		$user->idLanguage = 1;		// FIXME: Dle jazykové mutace
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
		if($this->twitterStatus == true){
			// Continue with connecting accounts - show user the options -> form
			// login part of form
			$group = $form->addGroup();
			$form->addText("nickname", "Nickname")
				->addRule(Form::FILLED,"Please fill the nickname.");
			$form->addPassword("password", "Password")
				->addRule(Form::FILLED,"Please fill the password.");
			$form->addSubmit("submitted", "Log in");
			$form->onSubmit[] = array($this, "loginFormSubmitted");
		} else {
			$form->addError(_("Twitter functions are not accessible right now. Please try it later."));
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
			$form->addError(_("Facebook functions have been temporary disabled. Please try it later."));
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
		$mail->setSubject("Thanks for your registration.");
		$mail->setBody($template);
		$mail->send();

		$this->getPresenter()->flashMessage("Thanks for your registration.");
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
		// If user was successfully logged and there were found data in session (namespace twitter) -> add connection
		$twitter = Environment::getSession("twitter");
		if($twitter){
			$user = Environment::getUser()->getIdentity();
			$exists = Leganto::connections()->getSelector()->exists($user->id,'twitter');
			if(!$exists){
				// Prepare user connection entity
				$connection = Leganto::connections()->createEmpty();
				$connection->user = $user->id;
				$connection->type = 'twitter';
				$connection->token = $twitter->auth['oauth_token'];

				// Commit
				Leganto::connections()->getInserter()->insert($connection);
			} else {
				// TODO: zobrazit uzivateli chybu, ze se snazi pripojit ucet, ktery je k nejakem uctu pripojen
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
		$twitter = Environment::getSession("twitter");

		// Prepare user entity
		$user = Leganto::users()->createEmpty();
		$user->role = "common";
		$user->idLanguage = 1;
		$user->inserted = new DibiVariable("now()", "sql");
		$user->nickname = $data->screen_name;
		// FIXME: odstranit vyplnove texty - v entitach jsem nenasel ze by byli vyzadovany, ale hazi to nullpointerexception
		$user->email = "dummy@example.com"; // dummy address - twitter won't share email address
		$user->password = "HJASD7889ASho6953Dsdfhsdfkododposqeih"; // not-hashed password - potencial security risk

		// Commit
		$user = Leganto::users()->getInserter()->insert($user);
		if($user != -1){
			// Prepare user connection entity
			$connection = Leganto::connections()->createEmpty();
			$connection->user = $user;
			$connection->type = 'twitter';
			$connection->token = $twitter->auth['oauth_token'];

			// Commit
			Leganto::connections()->getInserter()->insert($connection);

			// Login
			Environment::getUser()->authenticate(null,null,$twitter->auth['oauth_token']);
		} else {
			// TODO: tady vypsat chybu, ze takovy ucet uz existuje (stejny nick).
		}
		
	}
}

