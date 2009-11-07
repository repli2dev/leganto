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


	protected function permissionDenied() {
		$this->redirect(401);
	}

	protected function startUp() {
		parent::startup();
		// Authenticate user by token
		$token = Environment::getHttpRequest()->getQuery("token");
		// Is the logged user allowed to the action?
		$user = Environment::getUser();
		// $user->setIdentity(Environment::getUser()->getAuthenticationHandler()->authenticateByToken($token)); // FIXME: Je potreba vyresit prihlaseni dle tokenu, e.g. nelze pridat novou identitu
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
