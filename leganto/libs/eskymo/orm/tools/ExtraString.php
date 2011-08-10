<?php

/**
 * This class provides some extra methods associated with string.
 *
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\Tools;

use InvalidArgumentException;

class ExtraString {
	
	final private function __construct() {
		// Only static class
	}

	/**
	 * It checks if the string represents a datetime.
	 *
	 * @param string $datetime
	 * @throws InvalidArgumentException if given parameter is empty
	 * @return bool
	 */
	public static function isDateTime($datetime) {
		if (empty($datetime)) {
			throw new InvalidArgumentException("Empty datetime.");
		}
		return ereg("^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$", $datetime);
	}

	/**
	 * It returns string similarity using Levenshtein distance.
	 *
	 * @return int
	 */
	public static function similarity($first, $second) {
		return levenshtein($first, $second);
	}

	/**
	 * The PHP function lcFirst is available in the PHP version >= 5.3.0,
	 * this method does the same but in the lower PHP versions.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function lowerFirst($string) {
		static $from = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		static $to = "abcdefghijklmnopqrstuvwxyz";
		$first = substr($string, 0, 1);
		$rest = substr($string, 1, strlen($string) - 1);
		return strtr($first, $from, $to) . $rest;
	}

	/**
	 * It generates random string with specified length.
	 *
	 * @param int $length
	 * @return string
	 * @throws InvalidArgumentException if the $lenght is empty.
	 * @throws InvalidArgumentException if the length is not positive number.
	 */
	public static function random($length) {
		if (empty($length)) {
			throw new InvalidArgumentException("Empty length.");
		}
		if ($length <= 0) {
			throw new InvalidArgumentException("Length must be greater than zero.");
		}
		$chars = array_merge(range("a", "z"), range("A", "Z"), range(0, 9));
		$result = "";
		for ($i = 0; $i < $length; $i++) {
			$result .= $chars[rand(0, count($chars) - 1)];
		}
		return $result;
	}

	/**
	 * Truncate text after given length (even in middle of word)
	 * 
	 * @param string $s Text to truncate
	 * @param int $maxLen Maximal length of truncated text
	 * @param string $append Character to append after truncated text
	 * @return string hard truncated string
	 */
	public function hardTruncate($s, $maxLen, $append = "\xE2\x80\xA6") {
		if ($maxLen >= mb_strlen($s)) {
			return $s;
		} else {
			return mb_substr($s, 0, $maxLen - 1) . $append;
		}
	}

}
