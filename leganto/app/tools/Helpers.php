<?php

/**
 * Helper class to easy things up. Defines specific tasks on texts (texy), images (storage) etc.
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
final class Helpers {

	/** @var EditionImageStorage */
	private static $editionImageStorage;
	/** @var UserIconStorage */
	private static $userIconStorage;
	/** @var Texy */
	private static $texy;
	/** @var Texy */
	private static $texySafe;

	final private function __construct() {

	}

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
			case "texySafe": return array(get_class(), 'texySafeHelper');
				break;
			case "thumbnail": return array(get_class(), "thumbnailHelper");
				break;
			case "userIcon": return array(get_class(), "userIconHelper");
				break;
			case "hardTruncate": return array("ExtraString", "hardTruncate");
				break;
			case "bookCover": return array(get_class(), "bookCoverHelper");
				break;
			case "language": return array(get_class(), "languageHelper");
				break;
			default:
				throw new DataNotFoundException("helper: $helper");
		}
	}

	/**
	 * Return URL to random book cover (already thumbnailed) of given bookTitle
	 * @param int $bookTitleId
	 * @param int $width
	 * @param int $height
	 * @return URL of image
	 */
	public static function bookCoverHelper($bookTitleId, $width = NULL, $height = NULL) {
		return self::thumbnailHelper(
			self::getEditionImageStorage()->getRandomFileByBookTitleId($bookTitleId),
			$width,
			$height
		);
	}

	/**
	 * It returns date in format 'day.month.year'
	 *
	 * @param $date string Time in format 'YYYY-MM-DD HH:mm:ms'
	 * @return string Formated date.
	 */
	public static function dateFormatHelper($date) {
		return date('j.n.Y', strtotime($date));
	}

	/**
	 * Return HTML image to flag for given locale
	 * @param string $locale locale string (cs_CZ)
	 * @return string HTML image
	 */
	public static function languageHelper($locale) {
		$split = explode("_", $locale);
		$state = String::lower($split[1]);
		return "<img src=\"" . self::thumbnailHelper(WWW_DIR . "/img/flags/$state.png", 16) . "\" title=\"" . System::translate("Language") . "\" />";
	}

	/**
	 * It returns string which is processed by Texy! processor
	 * It should be only used for text written by users
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
	 * It returns string which is processed by Texy! processor
	 * Used on all content inserted by user
	 *
	 * @param string $input
	 * @return string The processed string
	 */
	public static function texySafeHelper($input) {
		if (empty(self::$texy)) {
			self::$texySafe = new Texy();
		}
		// FIXME: reportovat toto jako chybu.
		// TexyConfigurator::safeMode(self::$texySafe);
		self::$texySafe->allowed = array(
		    "script" => false,
		    "html/tag" => true,
		    "html/comment" => false,
		    "image/definition" => false,
		    "image" => false,
		    "phrase/strong+em" => true,
		    "phrase/strong" => true,
		    "phrase/em" => true,
		    "phrase/em-alt" => false,
		    "phrase/em-alt2" => false,
		    "phrase/ins" => false,
		    "phrase/del" => false,
		    "phrase/sup" => false,
		    "phrase/sup-alt" => true,
		    "phrase/sub" => false,
		    "phrase/sub-alt" => true,
		    "phrase/span" => true,
		    "phrase/span-alt" => false,
		    "phrase/cite" => false,
		    "phrase/quote" => true,
		    "phrase/acronym" => true,
		    "phrase/acronym-alt" => false,
		    "phrase/notexy" => false,
		    "phrase/code" => true,
		    "phrase/quicklink" => true,
		    "link/definition" => true,
		    "link/reference" => true,
		    "link/url" => true,
		    "link/email" => true,
		    "emoticon" => true,
		    "block/default" => true,
		    "block/pre" => false,
		    "block/code" => false,
		    "block/html" => false,
		    "block/text" => true,
		    "block/texysource" => false,
		    "block/comment" => false,
		    "block/div" => false,
		    "blocks" => false,
		    "figure" => false,
		    "horizline" => false,
		    "blockquote" => true,
		    "table" => false,
		    "heading/underlined" => false,
		    "heading/surrounded" => false,
		    "list" => true,
		    "list/definition" => false,
		    "typography" => true,
		    "longwords" => true
		);
		// Add smiles
		//:-)(šťastný) , :-D(vysmátý) , ;-)(šibalský) , :-((smutný) , :,-((plačící) , :-P(vyplazující jazyk) , >:((naštvaný) .
		$icons = array(
		    ':-)' => "01.png",
		    ':)' => "01.png",
		    ':-D' => "02.png",
		    ':D' => "02.png",
		    ';-)' => "03.png",
		    ';)' => "03.png",
		    ':-(' => "04.png",
		    ':,-(' => "05.png",
		    ':-P' => "06.png",
		    '>:(' => "07.png"
		);
		self::$texySafe->emoticonModule->root = "/img/smiles/";
		self::$texySafe->emoticonModule->icons = $icons;
		return self::$texySafe->process(self::wikiLinks($input));
	}

	/**
	 * Translate wiki-like links to real ones
	 * @param string input
	 * @return string ouptu
	 */
	public static function wikiLinks($string) {
		$patterns[0] = '/\[([^)^\]\|]+)\]/';
		$patterns[1] = '/\[([\S ^\]]+)\|([\S ^\]]+)\]/';
		// FIXME: jde to jinak než natvrdo?
		$replacement[0] = '<a href="/search/?query=\\1">\\1</a>';
		$replacement[1] = '<a href="/search/?query=\\2">\\2</a>';
		$string = preg_replace($patterns, $replacement, $string);
		return $string;
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
		$presenter = Environment::getApplication()->getPresenter();
		if (!is_string($image)) {
			$path = $image->path;
			$path = str_replace(WWW_DIR, "./", $path);
		} else {
			$path = $image;
		}
		if (!empty($width) && !empty($height)) {
			$url = $presenter->link("Thumb:resize", $path, $width, $height);
		} else
		if (!empty($width)) {
			$url = $presenter->link("Thumb:resize", $path, $width, NULL);
		} else
		if (!empty($height)) {
			$url = $presenter->link("Thumb:resize", $path, NULL, $height);
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
		return date('j.n.Y H:i', strtotime($time));
	}

	/**
	 * It returns thumbnail URL of user's icon.
	 *
	 * @param int $userId   User's ID
	 * @param int $width    Max width
	 * @param int $heightNull Max height
	 * @return string
	 */
	public static function userIconHelper($userId, $width = NULL, $height = NULL) {
		return self::thumbnailHelper(
			self::getUserIconStorage()->getFileById($userId),
			$width,
			$height
		);
	}

	// PRIVATE METHODS

	/** @return UserIconStorage */
	private static function getUserIconStorage() {
		if (self::$userIconStorage == null) {
			self::$userIconStorage = new UserIconStorage();
		}
		return self::$userIconStorage;
	}

	/** @return EditionImageStorage */
	private static function getEditionImageStorage() {
		if (self::$editionImageStorage == null) {
			self::$editionImageStorage = new EditionImageStorage();
		}
		return self::$editionImageStorage;
	}

}