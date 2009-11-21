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
			$domain = self::$domain;
			$r = new HttpRequest();
			self::$translator = new Translator(
				// FIXME:
				empty($domain) ? "EN_us" : $domain->locale,
				APP_DIR . "/locale",
				"leganto"
			);
		}
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
}