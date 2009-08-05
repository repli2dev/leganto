<?php
/**
 * This class provides some extra methods associated with string.
 *
 * @author Jan Papousek
 */
class ExtraString
{

	/**
	 * It generates random string with specified length.
	 *
	 * @param int $length
	 * @return string
	 * @throws NullPointerException if the $lenght is empty.
	 * @throws InvalidArgumentException if the length is not positive number.
	 */
	public static function random($length) {
		if (empty ($length))  {
			throw new NullPointerException("length");
		}
		if ($length <= 0) {
			throw new InvalidArgumentException("length");
		}
		$chars = array_merge(range("a","z"), range("A","Z"), range(0,9));
		$result = "";
		for($i=0; $i<$length; $i++) {
			$result .= $chars[rand(0,count($chars)-1)];
		}
		return $result;
	}

}