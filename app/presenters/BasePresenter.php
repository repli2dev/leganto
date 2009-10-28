<?php
/**
 * @author Jan Papousek
 */
class BasePresenter extends Presenter
{

	/* ERRORS */

	public function render500() {
		@ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 500 Internal server error");
		die();
	}

	public function render404() {
		@ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 404 Not Found");
		die();
	}

	/* PROTECTED METHODS */

	protected function beforeRender() {
		Header("Content-type: text/xml");
	}

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("texy", Helpers::getHelper('texy'));
		$template->registerHelper("translate", Helpers::getHelper('translate'));

		return $template;
	}


	// HTTP Authentication
	protected function httpAuthentication() {
		// Authentication is avaiable only via HTTPS
		if (!$this->getHttpRequest()->isSecured()) {
			return;
		}
		// Try to load [USER] and [PASSWORD] from URI
		if (empty($_SERVER["PHP_AUTH_USER"]) || empty($_SERVER["PHP_AUTH_PASS"])) {
			return;
		}
		try {
			Environment::getUser()->authenticate($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PASS"]);
		}
		catch (AuthenticationException $e) {
			Debug::processException($e);
			$this->permissionDenied();
		}
	}

	protected function permissionDenied() {
			Header('HTTP/1.1 401 Unauthorized');
			Header('WWW-Authenticate: Basic realm="Leganto API"');
			exit;
	}

	protected function startUp() {
		parent::startup();
		$this->httpAuthentication();
		// Is the logged user allowed to the action?
		$user = Environment::getUser();
		// The requested action.
		$method = $this->formatRenderMethod($this->view);
		if ($this->reflection->hasMethod($method)) {
			$reflection = $this->reflection->getMethod($method);
			// Is there annotation Secured which specifies the action type?
			if (Annotations::has($reflection, "Secured")) {
				$action = Annotations::get($reflection, "Secured")->action;
				if (!$user->isAllowed(IAuthorizator::ALL,$action)) {
					$this->permissionDenied();
				}
			}
		}
	}


}
