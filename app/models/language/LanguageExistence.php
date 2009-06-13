<?php
/**
 *				It checks existence of a language with the same name or locale
 *				in database.
 *
 * @author		Jan Papousek
 */
class LanguageExistence implements IExistence
{

	private $exists;

	/**
	 *			The constructor.
	 * @param	string	$locale		Locale of the language.
	 * @param	string	$name		Name of the language.
	 * @throws	NullPointerException if some of the params are empty.
	 */
	public function __construct($locale, $name) {
		$language = new Language();
		if (empty($locale)) {
			throw new NullPointerException("locale");
		}
		if (empty($name)) {
			throw new NullPointerException("name");
		}
		$row = dibi::query(
			"SELECT COUNT([id_language]) AS num
			 FROM [".$language->getTable()."]
			 WHERE [locale] = %s",$locale," OR [name] = %s", $name
		)->fetch();
		$this->exists = ($row["num"] > 0);
	}

	public function exists() {
		return $this->exists;
	}

}
?>
