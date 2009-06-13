<?php
/**
 *				This class looks after text expressions used on the site,
 *				which have to be localized.
 *
 * @author		Jan Papousek
 */
class Local extends Object implements ISingleton
{

	/**
	 *			The singleton instance
	 * @var		Site
	 */
	private static $singleton;

	/**
	 *			Localized texts.
	 *
	 * @var		array|(array|string)
	 */
	private $texts;

	/**
	 *			The construct has to be private becouse the class is singleton
	 */
	private function  __construct() {
		$currentLanguage = Site::getInstance()->getLanguage();
		$expresion = new Expresion();
		$this->texts = $expresion->get()->where(
			"[id_language] = %i", $currentLanguage["id_language"],
			"OR [id_language] = NULL"
		)->fetchAssoc("id_language,key");
	}

	/**
	 *			It return the instance of this singleton class.
	 * @return	Site
	 */
	public static function getInstance() {
		if (empty(self::$singleton)) {
			self::$singleton = new Expresions();
		}
		return self::$singleton;
	}
}
?>
