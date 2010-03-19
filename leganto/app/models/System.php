<?php
final class System
{

	/** @var DomainEntity	*/
	private static $domain;

	/** @var ITranslator	*/
	private static $translator;

	/** @var UserEntity		*/
	private static $user;

	public final function  __construct() {}

	/** @return IEntity		*/
	public static function domain() {
		if (!isset(self::$domain)) {
			$source = SimpleEntityFactory::createEntityFactory("domain")
				->getSelector()
				->findAll()
				->where("[uri] = %s", @$_SERVER['HTTP_HOST']);
				self::$domain = SimpleEntityFactory::createEntityFactory("domain")->fetchAndCreate($source);
		}
		return self::$domain;
	}

	/** @return ITranslator */
	public static function translator() {
		if (empty(self::$translator)) {
			$domain = self::domain();
			if(empty($domain)){
				$lang = "en_US";
			} else {
				$lang = $domain->locale;
			}
			self::$translator = new GettextTranslator(APP_DIR . '/locale/' . $lang . '/LC_MESSAGES/messages.mo');
		}
		// FIXME: nastavit cachovani
		return self::$translator;
	}

	/** @return UserEntity */
	public static function user() {
		if (!isset(self::$user)) {
			if (!Environment::getUser()->isAuthenticated()) {
				self::$user = NULL;
			}
			else {
				self::$user = Leganto::users()
					->getSelector()
					->find(Environment::getUser()->getIdentity()->id);
			}
		}
		return self::$user;
	}

	/** @return string */
	public static function translate($message, $count = 1) {
		return self::translator()->translate($message, $count = 1);
	}
}