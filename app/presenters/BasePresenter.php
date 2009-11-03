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
 */
class BasePresenter extends Presenter
{

	/* PROTECTED METHODS */

	protected function beforeRender() {
	//	Header("Content-type: text/xml");
	}

	protected function code($code, $msg = NULL) {
		$headers = array(
			200		=> "OK",
			401		=> "Unauthorized",
			403		=> "Forbidden",
			404		=> "Not found",
			500		=> "Internal server error",
		);
		@ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 " . $code . " " . (isset($headers[$code]) ? $headers[$code] : ""));
		echo $msg;
		die();
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
	// FIXME: Nefunguje
	protected function httpAuthentication() {
		// Authentication is avaiable only via HTTPS
		if (!$this->getHttpRequest()->isSecured()) {
			return;
		}
		// Try to load [USER] and [PASSWORD] from URI
		if (empty($_SERVER["PHP_AUTH_USER"]) || empty($_SERVER["PHP_AUTH_PASS"])) {
			$this->permissionDenied();
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
		//$this->httpAuthentication();
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
