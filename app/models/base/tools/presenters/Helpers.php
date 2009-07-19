<?php
/*
 * The web basis called Eskymo.
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        [--- ESKYMO REPOSITORY LINK ---]
 * @category    Eskymo
 * @package     Eskymo
 * @version     2009-07-04
 */

/*namespace Eskymo/Tools;*/

/**
 * The static class which provides filters to presenters.
 *
 * @author Jan Papousek
 */
final class Helpers
{

	final private function  __construct() {}

	/**
	 * It returns the callback for helper with given name
	 * @param string $helper The name of helper.
	 * @return callback The callback to the helper.
	 * @throws NullPointerException if the $helper is empty.
	 * @throws DataNotFoundException if the helper does not exist.
	 */
	public static function getHelper($helper) {
		if (empty($helper)) {
			throw NullPointerException("helper");
		}
		switch ($helper) {
			case "date": return array(get_class(), 'dateFormatHelper');
				break;
			case "time": return array(get_class(), 'timeFormatHelper');
				break;
			case "texy": return array(get_class(), 'texyHelper');
				break;
			case "translate": return array(get_class(), 'translateHelper');
			default:
				throw new DataNotFoundException("helper: $helper");
		}
	}

	/**
	 * It returns date in format 'day.month.year'
	 *
	 * @param $date string Time in format 'YYYY-MM-DD HH:mm:ms'
	 * @return string Formated date.
	 */
	public static function dateFormatHelper($date) {
		return preg_replace(
			"/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/",
			"\\3. \\2. \\1",
			$date
		);
	}

	/**
	 * It returns string which is processed by Texy! processor
	 *
	 * @param string $input
	 * @return string The processed string
	 */
	public static function texyHelper($input) {
		// TODO: Process by Texy!
		return $input;
	}

	/**
	 * It returns time in format 'day.month.year, hour:second'
	 *
	 * @param $time string Time in format 'YYYY-MM-DD HH:mm:ms'
	 * @return string Formated time.
	 */
	public static function timeFormatHelper($time) {
		return preg_replace(
			"/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/",
			"\\3. \\2. \\1, \\4:\\5",
			$time
		);
	}

	/**
	 * It return localized key.
	 *
	 * @param string $module The module name.
	 * @param string $key The key.
	 */
	public static function translateHelper($key, $module = "base") {
		return Locales::get($module)->get($key);
	}

}