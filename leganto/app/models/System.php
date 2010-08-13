<?php
final class System
{

	/** @var DomainEntity	*/
	private static $domain;

	/** @var LanguageEntity */
	private static $language;

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
			$cache = Environment::getCache("translator");
			if(isSet($cache["translator"])){
				return $cache["translator"];
			} else {
				$domain = self::domain();
				if(empty($domain)){
					$lang = "en_US";
				} else {
					$lang = $domain->locale;
				}
				self::$translator = new GettextTranslator(APP_DIR . '/locale/' . $lang . '/LC_MESSAGES/messages.mo');
				$cache->save("translator", self::$translator, array(
					'expire' => time() + 60 * 60 * 6,	// expire in 6 hours
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
		$args = func_get_args();
		return call_user_func_array(array(self::translator(), 'translate'), $args);
	}

	public static function captchaQuestion() {
		$data = Leganto::captcha()->getSelector()->findFromLanguageRandom(self::language()->getId());
		return $data;
	}
}