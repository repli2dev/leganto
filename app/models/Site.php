<?php

class Site extends Object implements ISingleton
{

	/**
	 *			The language used on site (info from MySQL table 'language').
	 * @var		array|mixed
	 */
	private $language;

	/**
	 *			The site descriptor (info from MySQL table 'site').
	 * @var		array|mixed
	 */
	private $domain;

	/**
	 *			The singleton instance
	 * @var		Site
	 */
	private static $singleton;

	/**
	 *			The construct has to be private becouse the class is singleton
	 */
	private function  __construct() {
		$domain = new Domain();
		$rows = $domain->get()->where("[uri] = %s", @$_SERVER['HTTP_HOST']);
		if ($rows->count() == 0) {
			throw new DataNotFoundException("domain");
		}
		$this->domain = $rows->fetch();
		$language = new Language();
		$this->language = $language->get()
				->where("[id_language] = %i", $this->domain["id_language"])
				->fetch();
	}

	/**
	 *			It return the instance of this singleton class.
	 * @return	Site
	 */
	final public static function getInstance() {
		if (empty(self::$singleton)) {
			self::$singleton = new Site();
		}
		return self::$singleton;
	}

	/**
	 *			It returns a language used on the site. It contains columns
	 *			described in doc to MySQL table 'language'.
	 * 
	 * @return	array|mixed		The language descriptor.
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 *			It return a site descriptor of current site instance.
	 *			It contains columns described in doc to MySQL table 'site'.
	 *
	 * @return	array|mixed		The site descriptor.
	 */
	public function getDomain() {
		return $this->site;
	}

	public function __clone() {}

	public function __wakeup();

}
?>
