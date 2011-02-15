<?php
class YazRecord {

	private $data;

	private function  __construct(array $data) {
		$this->data = $data;
	}

	/** @return YazRecord */
	public static function fromXml($string) {
		$xml = @simplexml_load_string(utf8_encode($string));
		if ($xml === FALSE) {
			throw new IOException("Unexpected error has happened.");
		}
		$data = array();
		foreach($xml->datafield AS $datafield) {
			$attributes = $datafield->attributes();
			switch($attributes['tag']) {
				// ISBN
				case '020':
					$isbn = ExtraArray::firstValue(explode(' ', self::getSpecificSubfield($datafield, 'a')));
					if (!empty($isbn)) {
						$data['isbn'] =	$isbn;
					}
					break;
				// Author
				case '100':
					if (!isset($data['authors'])) $data['authors'] = array();
					$data['authors'][] = trim(self::getSpecificSubfield($datafield, 'a'), ', ');
					break;
				// Title statement
				case '245':
					$data['title'] = trim(self::getSpecificSubfield($datafield, 'a'), '/; ');
					break;
				// Edition
				case '260':
					$place		= trim(self::getSpecificSubfield($datafield, 'a'), ': ');
					$publisher	= trim(self::getSpecificSubfield($datafield, 'b'), ', ');
					$year		= self::getSpecificSubfield($datafield, 'c');
					if (!empty($place)) {
						$data['published-place'] = $place;
					}
					if (!empty($publisher)) {
						$data['publisher'] = $publisher;
					}
					if (!empty($year)) {
						$data['published-year']= $year;
					}
					break;
				// Numbe of pages
				case '300':
					preg_match("/\d+/", self::getSpecificSubfield($datafield, 'a'), $matches);
					$data['pages'] = ExtraArray::firstValue($matches);
					break;

			}
		}
		return new YazRecord($data);
	}

	private static function getSpecificSubfield($datafield, $code) {
		foreach($datafield->subfield AS $subfield) {
			$attributes = $subfield->attributes();
			if ((string) $attributes['code'] == $code) {
				return (string) $subfield;
			}
		}
	}

}
