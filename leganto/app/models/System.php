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
			self::$domain = SimpleEntityFactory::createEntityFactory("domain")
				->getSelector()
				->findAll()
				->where("[uri] = %s", @$_SERVER['HTTP_HOST']);
		}
		return self::$domain;
	}

	/** @return ITranslator */
	public static function translator() {
		if (empty(self::$translator)) {
			self::$translator = new SimpleTranslator();
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


class SimpleTranslator implements ITranslator
{

	public function translate($message, $count = NULL) {
		return $message;
	}

}