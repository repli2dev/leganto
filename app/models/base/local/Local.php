<?php
/**
 * This class looks after text expressions used on the site,
 * which have to be localized.
 *
 * @author Jan Papousek
 */
class Local extends Object implements ISingleton
{

	/**
	 * The singleton instance
	 *
	 * @var Site
	 */
	private static $singleton;

	/**
	 * Default texts.
	 *
	 * @var array|(array|string)
	 */
	private $defaultTexts;

	/**
	 * Localized texts.
	 *
	 * @var array|(array|string)
	 */
	private $localizedTexts;

	/**
	 * The construct has to be private becouse the class is singleton
	 */
	private function  __construct() {
		$useDatabase = Environment::getConfig("locales")->database;
		if ($useDatabase) {
			$this->loadDataFromDatabase();
		}
		else {
			$this->loadDataFromProperties();
		}
		
	}

	/**
	 * Ii returns a localized string or its default value.
	 *
	 * @param string $key The key name of the text.
	 * @throws NullPointerException if the $key is empty.
	 * @throws DataNotFoundException if the key was not found.
	 */
	public function get($key) {
		if (empty($key)) {
			throw new NullPointerException("key");
		}
		if (!empty($this->localizedTexts) &&!empty($this->localizedTexts[$key])) {
			return $this->localizedTexts[$key];
		}
		if (!empty ($this->defaultTexts[$key])) {
			return $this->defaultTexts[$key];
		}
		throw new DataNotFoundException("key");
	}

	/**
	 * It return the instance of this singleton class.
	 *
	 * @return Local
	 */
	public static function getInstance() {
		if (empty(self::$singleton)) {
			self::$singleton = new Local();
		}
		return self::$singleton;
	}

	/**
	 * It loads data from database.
	 */
	private function loadDataFromDatabase() {
		$currentLanguage = Site::getInstance()->getLanguage();
		$expresion = new Expresion();
		$this->localizedTexts = $expresion->get()->where(
			"%n = %i",
			Expresion::DATA_LANGUAGE,
			$currentLanguage[Language::DATA_ID]
		)->fetchAll();
		$this->defaultTexts = $expresion->get()->where(
			"%n = %sql",
			Expresion::DATA_LANGUAGE,
			"NULL"
		)->fetchAll();
	}

	/**
	 * It loads data from properties.
	 *
	 * @throws DataNotFoundException if there is no file with locales to load.
	 */
	private function loadDataFromProperties() {
		$currentLanguage = Site::getInstance()->getLanguage();
		// Localized texts
		$localizedFile = LOCALES_DIR . "/" . $currentLanguage[Language::DATA_LOCALE] . ".ini";
		if (file_exists($localizedFile)) {
			$this->localizedTexts = Config::fromFile($localizedFile)->getArrayCopy();
		}
		// Default texts
		$defaultFile =  LOCALES_DIR . "/default.ini";
		if (file_exists($defaultFile)) {
			$this->defaultTexts = Config::fromFile($defaultFile)->getArrayCopy();
		}
		else {
			throw new DataNotFoundException($defaultFile);
		}
	}

	public function __clone() {}

	public function __wakeup() {}
}
