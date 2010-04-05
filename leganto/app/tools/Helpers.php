<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
final class Helpers
{

	/** @var Texy */
	private static $texy;

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
			case "thumbnail": return array(get_class(), "thumbnailHelper");
				break;
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
		return date('d.m.Y',strtotime($date));
	}

	/**
	 * It returns string which is processed by Texy! processor
	 *
	 * @param string $input
	 * @return string The processed string
	 */
	public static function texyHelper($input) {
		if (empty(self::$texy)) {
		    self::$texy = new Texy();
		}
		return self::$texy->process($input);
	}

	/**
	 * It returns thumbnail URL
	 *
	 * @param string $image Image path
	 * @param int $width Max width
	 * @param int $height Max height
	 * @return string
	 */
	public static function thumbnailHelper($image, $width = NULL, $height = NULL) {
		if (empty($image) || (!file_exists($image) && !file_exists(WWW_DIR . $image))) {
			$image = WWW_DIR . "/img/avatar_placeholder.gif";
		}
		$url = Environment::getApplication()->getPresenter()->getTemplate()->baseUri . "thumb/phpThumb.php?src=" . $image;
		if (!empty($width)) {
			$url .= "&w=$width";
		}
		if (!empty($height)) {
			$url .= "&h=$height";
		}
		return $url;
	}

	/**
	 * It returns time in format 'day.month.year, hour:second'
	 *
	 * @param $time string Time in format 'YYYY-MM-DD HH:mm:ms'
	 * @return string Formated time.
	 */
	public static function timeFormatHelper($time) {
		return date('d.m.Y H:i',$time);
	}

}