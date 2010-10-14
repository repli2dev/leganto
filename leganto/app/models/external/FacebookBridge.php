<?php

/**
 * Facebook bridge (battle of Khazad-dum) - hope there wont be any changes
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class FacebookBridge implements ISocialNetwork {

	private $gate;
	private $status = false;
	private $session;

	function __construct() {
		// Open session namespace for facebook data
		$this->session = Environment::getSession("facebook");
		$this->session->setExpiration(60*5);
	}

	/**
	 * Should do login aganist social network API and return unique ID. It store retrieved data into session.
	 * @return boolean success of operation
	 */
	function authentication() {
		if (!$this->isEnabled())
			return false;
		// Create connection to facebook and login
		$this->doNormalConnection();
		// Obtain session
		$session = $this->gate->getSession();
		if (!$session) {
			header("Location: " . $this->gate->getLoginUrl(array(
				    'req_perms' => 'publish_stream,read_stream,offline_access,status_update,share_item'
				)));
		}
		// Obtain user ID
		$this->session->user = $this->gate->getUser();

		if (!empty($this->session->user)) {
			return true;
		} else {
			return false;
		}
	}

	function isEnabled() {
		if (Environment::getConfig("facebook")->enable) { // check if facebook is enabled
			return true;
		} else {
			return false;
		}
	}

	function doNormalConnection() {
		$this->gate = new Facebook(array(
			    "appId" => Environment::getConfig("facebook")->apiKey,
			    "secret" => Environment::getConfig("facebook")->secret,
			    "cookie" => true
			));
	}

	/**
	 * Return user's data - that includes full name and last status.
	 * @param array $data;
	 */
	function userInfo() {
		if (!$this->isEnabled())
			return false;
		// Create connection
		$this->doNormalConnection();
		if (empty($this->session->user)) {
			throw new NullPointerException("User ID is empty, cannot fetch user information.");
		}
		$data = $this->gate->api('/me');
		// Username is quite important field and can be empty -> solve that
		if (empty($data["username"])) {
			$data["username"] = String::webalize(str_replace(" ", "", $data["name"]), NULL, FALSE);
		}
		return $data;
	}

	/*
	 * Do login and set current instance to logged.
	 */

	function doLogin() {
		if (!$this->isEnabled())
			return false;
		// Try if session is filled with right data (because then it ok to skip authentication)
		if (isset($this->session->user)) {
			$this->status = true;
		} else
		if ($this->authentication()) { // Authentication was successful
			$this->status = true;
		} else {
			$this->status = false;
		}
	}

	/**
	 * Do login and tries to authenticate aganist our connection table
	 */
	function doLoginWithAuthentication() {
		$this->doLogin();
		// If status is true then try to look up for existing connection -> if it is found then user is logged automatically
		if ($this->status == true) {
			try {
				Environment::getUser()->authenticate(null, null, $this->session->user);
				$this->destroyLoginData();
			} catch (AuthenticationException $e) {
				Debug::processException($e);
				// Silent error - output is not desired - connection was not found -> show message to let user choose what to do
			}
		}
	}

	/**
	 * Return true if current instance has successful log in (authentication was success or existing data in session were found)
	 * @return boolean
	 */
	function isLogged() {
		if (!$this->isEnabled())
			return false;
		return $this->status;
	}

	/**
	 * Return unique user token, if token is empty then return null
	 * @return string 
	 */
	function getToken() {
		if (!$this->isEnabled())
			return null;
		if (!empty($this->session->user)) {
			return $this->session->user;
		} else {
			return null;
		}
	}

	/**
	 * Post message on facebook, return true if successful
	 * Usage: $this->postMessage("Flies cleared.",Leganto::connections()->getSelector()->getToken(System::user()->id,"facebook"));
	 * @return boolean
	 */
	function postMessage($message, $uid) {
		if (!$this->isEnabled())
			return false;

		if (empty($uid)) {
			throw new NullPointerException("uid");
		}

		$this->doNormalConnection();
		$this->gate->api_client->users_setStatus($message, $uid);
	}

	function destroyLoginData() {
		// Remove session informations belonging to connection
		if (isset($this->session)) {
			$this->session->remove();
		}
	}

}
