<?php

/**
 * Twitter bridge (god be praised)
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class TwitterBridge implements ISocialNetwork {

	private $gate;
	private $status = false;
	private $session;

	function __construct() {
		// Open session namespace for twitter data
		$this->session = Environment::getSession("twitter");
		$this->session->setExpiration(60*5);
	}

	/**
	 * Should do login aganist social network API and return unique ID. It store retrieved data into session.
	 * @return boolean success of operation
	 */
	function authentication() {
		if (!$this->isEnabled())
			return false;
		// Get GET variable
		$get = Environment::getHttpRequest();
		$oauth_verifier = $get->getQuery('oauth_verifier');

		if (!isset($oauth_verifier)) { // user is not logged here -> prepare redirect to twitter login server
			// Create instance of bridge to twitter OAuth
			$this->gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret);

			// Get REQUEST token (set callbeck to current URL)
			$requestToken = $this->gate->getRequestToken(Environment::getHttpRequest()->uri->absoluteUri);

			// Save it to session
			$this->session->request_token_key = $token = $requestToken['oauth_token'];
			$this->session->request_token_secret = $requestToken['oauth_token_secret'];

			// Failsafe - if last connection failed then stop trying
			switch ($this->gate->http_code) {
				case 200:
					// ADD request token to URL where should user authorize
					$url = $this->gate->getAuthorizeURL($token);
					header('Location: ' . $url);
					break;
				default:
					return false;
					break;
			}
		} else { // user has returned - proceed, verify and store user's data
			// Create bridge to twitter to verify received informations.
			$this->gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret, $this->session->request_token_key, $this->session->request_token_secret);
			$this->gate->get('account/verify_credentials');

			// Remove token key etc. - it's useless now.
			unset($this->session->request_token_key);
			unset($this->session->request_token_secret);
			;

			// Fetch informations about user
			$this->session->auth = $this->gate->getAccessToken($_GET['oauth_verifier']);
			return true;
		}
	}

	function isEnabled() {
		if (Environment::getConfig("twitter")->enable) { // check if twitter is enabled
			return true;
		} else {
			return false;
		}
	}

	function doNormalConnection() {
		$this->gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret, $this->session->auth['oauth_token'], $this->session->auth['oauth_token_secret']);
	}

	/**
	 * Return user's data - that includes full name and last status.
	 * @param array $data;
	 */
	function userInfo() {
		if (!$this->isEnabled())
			return false;

		// Fetch data
		$this->doNormalConnection();
		$data = $this->gate->get('account/verify_credentials');
		return $data;
	}

	/*
	 * Do login and set current instance to logged.
	 */

	function doLogin() {
		if (!$this->isEnabled())
			return false;
		// Try if session is filled with right data (because then it ok to skip authentication)
		if (isset($this->session->auth['oauth_token']) && isset($this->session->auth['oauth_token_secret'])) {
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
				Environment::getUser()->authenticate(null, null, $this->session->auth['oauth_token']);
				$this->destroyLoginData();
			} catch (AuthenticationException $e) {
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
		if (!empty($this->session->auth['oauth_token'])) {
			return $this->session->auth['oauth_token'];
		} else {
			return null;
		}
	}

	/**
	 * Post message on twitter, return true if successful
	 * @return boolean
	 */
	function postMessage($message) {
		if (!$this->isEnabled())
			return false;

		// Post data
		$this->doNormalConnection();
		$data = $this->gate->post('statuses/update', array("status" => $message));
		if (!isset($data->error)) {
			return true;
		} else {
			return false;
		}
	}

	function destroyLoginData() {
		// Remove session information belonging to connection
		if (isset($this->session)) {
			$this->session->remove();
		}
	}

}
