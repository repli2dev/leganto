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
		$user->idLanguage = 1;
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
		$form = new BaseForm();
		$form->getElementPrototype()->setId("sign");
		
		if(Environment::getConfig("twitter")->enable) { // check if twitter is enabled
			if(!isset($_GET['oauth_verifier'])) { // user is not logged here -> prepare redirect to twitter login server
				// Create instance of bridge to twitter OAuth
				$gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret);

				// Get REQUEST token (set callbeck to current URL)
				$requestToken = $gate->getRequestToken(Environment::getHttpRequest()->uri->absoluteUri);

				// Save it to session
				$_SESSION['request_token_key'] = $token = $requestToken['oauth_token'];
				$_SESSION['request_token_secret'] = $requestToken['oauth_token_secret'];

				// Failsafe - if last connection failed then stop trying
				switch ($gate->http_code) {
					case 200:
						// ADD request token to URL where should user authorize
						$url = $gate->getAuthorizeURL($token);
						header('Location: '.$url);
						break;
					default:
						_('Could not connect to Twitter. Please try it later.');
						break;
				}
			} else { // user has returned - proceed, verify and store user's data
				// Create bridge to twitter to verify received informations.
				$gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret, $_SESSION['request_token_key'], $_SESSION['request_token_secret']);

				// Remove token key etc. - it's useless now.
				unset($_SESSION['request_token_key']);
				unset($_SESSION['request_token_secret']);;

				// Fetch informations about user
				$info = $gate->getAccessToken($_GET['oauth_verifier']);
				var_dump($info);
				$test = $gate->get('account/verify_credentials');
				var_dump($test);

				// Show user a dialog - create new account, or add this twitter to normal account
				// TODO: joining and creating accounts
			}
		} else {
			$form->addError(_("Twitter functions have been temporary disabled. Please try it later."));
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
}

