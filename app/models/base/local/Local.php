<?php
/**
 * The basic implentation of the ILocal interface.
 *
 * This class loads ini files which are called {$moduleName}.{$local}.ini
 * and are located in LOCALES_DIR
 *
 * @author Jan Papousek
 */

class Local extends Object implements ILocal
{

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
	 * Module name.
	 *
	 * @var string
	 */
	private $moduleName;

	/**
	 * It creates a new instance of this class.
	 *
	 * @param string $directory The directory path to the locales.
	 * @param string $name The name of the module which is localized.
	 * @throws NullPointerException if the $directory or $name is empty.
	 * @throws IOException if the default file is not found.
	 */
	public function  __construct($directory, $name = "base") {
		if (empty($directory)) {
			throw new NullPointerException("directory");
		}
		if (empty($name)) {
			throw new NullPointerException("name");
		}
		$this->moduleName = $name;
		$currentLanguage = Site::getInstance()->getLanguage();
		// Localized texts
		$localizedFile = LOCALES_DIR . "/" . $name . "." . $currentLanguage[Language::DATA_LOCALE] . ".ini";
		if (file_exists($localizedFile)) {
			$this->localizedTexts = Config::fromFile($localizedFile)->getArrayCopy();
		}
		// Default texts
		$defaultFile =  LOCALES_DIR . "/" . $name . ".default.ini";
		if (file_exists($defaultFile)) {
			$this->defaultTexts = Config::fromFile($defaultFile)->getArrayCopy();
		}
		else {
			throw new IOException($defaultFile);
		}
	}

	/**
	 * It returns a localized string or its default value.
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
		//foreach ($this->defaultTexts AS $key => $value);
		throw new DataNotFoundException("The lokalized key '$key' not found in module '$this->moduleName'.");
	}

	/**
	 * It returns a module name.
	 *
	 * @return string
	 */
	public function getModule() {
		return $this->moduleName;
	}
}
?>
