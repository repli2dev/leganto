<?php
/**
 * This class looks after text expressions used on the site,
 * which have to be localized.
 *
 * @author Jan Papousek
 */
class Locales extends Object
{

	/**
	 * The array of localized modules.
	 * 
	 * @var array|ILocal
	 */
	private static $locales = array();

	/**
	 * It returns localization of the module.
	 *
	 * @param string $moduleName The module name.
	 * @return ILocal Localization of the module.
	 * @throws NullPointerException if the $moduleName is empty.
	 * @throws IOException if the localization does not exist.
	 * @throws DataNotFoundException if the module does not exist.
	 */
	public static function get($moduleName = "base") {
		if (empty($moduleName)) {
			throw new NullPointerException($moduleName);
		}
		if (empty(self::$locales[$moduleName])) {
			self::$locales[$moduleName] = new Local(
				Modules::getInstance()->get($moduleName)->getDirectoryWithLocales(),
				$moduleName
			);
		}
		return self::$locales[$moduleName];
	}

}
