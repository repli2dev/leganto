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

class Twitter implements ISocialNetwork {

	private $gate;

	/**
	 * Should do login aganist social network API and return unique ID. It store retrieved data into session.
	 * @return boolean success of operation
	 */
	function authentification() {
		if(!$this->isEnabled()) return false;
		// Get GET variable
		$get = Environment::getHttpRequest();
		$oauth_verifier = $get->getQuery('oauth_verifier');
		
		// Open session namespace for twitter data
		$session = Environment::getSession("twitter");
		
		if(!isset($oauth_verifier)) { // user is not logged here -> prepare redirect to twitter login server
			// Create instance of bridge to twitter OAuth
			$this->gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret);

			// Get REQUEST token (set callbeck to current URL)
			$requestToken = $this->gate->getRequestToken(Environment::getHttpRequest()->uri->absoluteUri);

			// Save it to session
			$session->request_token_key = $token = $requestToken['oauth_token'];
			$session->request_token_secret = $requestToken['oauth_token_secret'];

			// Failsafe - if last connection failed then stop trying
			switch ($this->gate->http_code) {
				case 200:
				// ADD request token to URL where should user authorize
					$url = $this->gate->getAuthorizeURL($token);
					header('Location: '.$url);
					break;
				default:
					return false;
					break;
			}
		} else { // user has returned - proceed, verify and store user's data
			// Create bridge to twitter to verify received informations.
			$this->gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret, $session->request_token_key, $session->request_token_secret);

			// Remove token key etc. - it's useless now.
			unset($session->request_token_key);
			unset($session->request_token_secret);;

			// Fetch informations about user
			$session->auth = $this->gate->getAccessToken($_GET['oauth_verifier']);
			return true;
		}
	}

	/**
	 * Return user's data - that includes full name and last status.
	 * @param array $data;
	 */
	function userInfo() {
		if(!$this->isEnabled()) return false;
		// Open session namespace for twitter data
		$session = Environment::getSession("twitter");

		// Fetch data
		$this->gate = new TwitterOAuth(Environment::getConfig("twitter")->apiKey, Environment::getConfig("twitter")->secret, $session->auth['oauth_token'], $session->auth['oauth_token_secret']);
		$data = $this->gate->get('account/verify_credentials');
		return $data;
	}

	function isEnabled() {
		if(Environment::getConfig("twitter")->enable) { // check if twitter is enabled
			return true;
		} else {
			return false;
		}
	}

}
