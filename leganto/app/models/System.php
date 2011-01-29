<?php

/**
 * System class maintain domains <-> languages, translating, user identity and logging
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
final class System {

	/** @var DomainEntity	 */
	private static $domain;
	/** @var LanguageEntity */
	private static $language;
	/** @var ITranslator	 */
	private static $translator;
	/** @var UserEntity		 */
	private static $user;

	public final function __construct() {

	}

	/** @return IEntity		 */
	public static function domain() {
		if (!isset(self::$domain)) {
			$source = SimpleEntityFactory::createEntityFactory("domain")
					->getSelector()
					->findAll()
					->where("[uri] = %s", self::getHTTPHost());
			self::$domain = SimpleEntityFactory::createEntityFactory("domain")->fetchAndCreate($source);
		}
		return self::$domain;
	}

	private static function getHTTPHost() {
		return ltrim(@$_SERVER['HTTP_HOST'],"www.");
	}

	/** @return IEntity */
	public static function language() {
		if (!isset(self::$language)) {
			self::$language = SimpleEntityFactory::createEntityFactory("language")
					->getSelector()
					->find(self::domain()->idLanguage);
		}
		return self::$language;
	}

	/** @return ITranslator */
	public static function translator() {
		if (empty(self::$translator)) {
			// Try to find translator in cache
			$cache = Environment::getCache("translator");
			if (isSet($cache["translator"])) {
				return $cache["translator"];
			} else {
				// If unsuccessful then create new
				$domain = self::domain();
				if (empty($domain)) {
					$lang = "en_US";
				} else {
					$lang = $domain->locale;
				}
				self::$translator = new GettextTranslator(APP_DIR . '/locale/' . $lang . '/LC_MESSAGES/messages.mo');
				$cache->save("translator", self::$translator, array(
				    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
				));
			}
		}
		return self::$translator;
	}

	/** @return UserEntity */
	public static function user() {
		if (!isset(self::$user)) {
			if (!Environment::getUser()->isAuthenticated()) {
				self::$user = NULL;
			} else {
				self::$user = Leganto::users()
						->getSelector()
						->find(Environment::getUser()->getIdentity()->id);
			}
		}
		return self::$user;
	}

	/** @return string */
	public static function translate($message, $count = 1) {
		$args = func_get_args();
		return call_user_func_array(array(self::translator(), 'translate'), $args);
	}

	/** @return mixed */
	public static function captchaQuestion() {
		$data = Leganto::captcha()->getSelector()->findFromLanguageRandom(self::language()->getId());
		return $data;
	}
	
	/** @return bool */
	public static function isCurrentlyLogged($user) {
		if(System::user() !== null && $user === System::user()->getId()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Log text into db along with id_user, url and time
	 * @param string $text text to be logged
	 * @param int $suser Only when need different one than currently logged user!
	 * @return NULL
	 */
	public static function log($text = NULL,$user = NULL) {
		// Prepare for insertion and do it without System::user and entities to save time
		if(!empty($user)) {
			$values["id_user"] = $user;
		} else {
			$values["id_user"] = Environment::getUser()->getIdentity()->id;
		}
		if (empty($values["id_user"])) {
			return;
		}
		$uri = Environment::getHttpRequest()->getUri();
		$values["url"] = $uri->path;
		if (!empty($uri->query)) {
			$values["url"] .= "?" . $uri->query;
		}
		$values["text"] = $text;
		$values["time"] = new DateTime;
		// Add tracking data
		$values["ip"] = $_SERVER["REMOTE_ADDR"];
		$values["browser"] = $_SERVER['HTTP_USER_AGENT'];
		try {
			return dibi::insert("user_log", $values)->execute();
		} catch (Exception $e) {
			Debug::processException($e, TRUE);
		}
	}

}