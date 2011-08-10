<?php
/**
 * Base Presenter for API
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://git.yavanna.cz/?p=leganto.git
 * @author		Jan Papousek
 * @author		Jan Drabek
 */
namespace ApiModule;

use Nette\Reflection\ClassType,
 Nette\Security\AuthenticationException,
 Nette\Security\IAuthorizator,
	Nette\Diagnostics\Debugger;

class BasePresenter extends \BasePresenter {

	protected function beforeRender() {
		// Send correct header and supress errors
		Header("Content-type: text/xml");
		error_reporting(0);
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

	// HTTP Authentication
	protected function httpAuthentication() {
		// Authentication is available only via HTTPS
		if (!$this->getHttpRequest()->isSecured()) {
			return;
		}
		// Try to load [USER] and [PASSWORD] from header
		if (empty($_SERVER["PHP_AUTH_USER"]) || empty($_SERVER["PHP_AUTH_PW"])) {
			$this->permissionDenied();
		}
		try {
			$this->getUser()->login($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PW"]);
		}
		catch (AuthenticationException $e) {
			Debugger::processException($e);
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
		$user = $this->getUser();
		// The requested action.
		$method = $this->formatRenderMethod($this->view);
		if ($this->reflection->hasMethod($method)) {
			$reflection = $this->reflection->getMethod($method);
			// Is there annotation Secured which specifies the action type?
			if ($reflection->hasAnnotation("Secured")) {
				$action = $reflection->getAnnotation("Secured");
				if (!$user->isAllowed(IAuthorizator::ALL,$action)) {
					$this->permissionDenied();
				}
			}
		}
	}


}
