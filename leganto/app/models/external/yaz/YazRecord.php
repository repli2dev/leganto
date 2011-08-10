<?php

/**
 * YAZ Record
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\External\Yaz;

use Nette\IOException,
    Leganto\Tools\ExtraArray;

class YazRecord {

	private $data;

	private function __construct(array $data) {
		$this->data = $data;
	}

	public function getData() {
		return (object) $this->data;
	}

	/** @return YazRecord */
	public static function fromXml($string) {
		$xml = @simplexml_load_string($string);
		if ($xml === FALSE) {
			throw new IOException("Unexpected error has happened.");
		}
		$data = array();
		foreach ($xml->datafield AS $datafield) {
			$attributes = $datafield->attributes();
			switch ($attributes['tag']) {
				// ISBN
				case '020':
					preg_match("/(\d|-)+/", self::getSpecificSubfield($datafield, 'a'), $matches);
					$isbn = ExtraArray::firstValue($matches);
					if (!empty($isbn)) {
						$data['isbn'] = $isbn;
					}
					break;
				// Language
				case '041':
					$language = self::getSpecificSubfield($datafield, 'a');
					if (!empty($language)) {
						$data['language'] = $language;
					}
					break;
				// Author
				case '100':
					if (!isset($data['authors']))
						$data['authors'] = array();
					$data['authors'][] = trim(self::getSpecificSubfield($datafield, 'a'), ', ');
					break;
				// Title statement
				case '245':
					$data['title'] = trim(self::getSpecificSubfield($datafield, 'a'), '/.;: ');
					break;
				// Edition
				case '260':
					$place = trim(self::getSpecificSubfield($datafield, 'a'), ': ');
					$publisher = trim(self::getSpecificSubfield($datafield, 'b'), ', ');
					$year = self::getSpecificSubfield($datafield, 'c');
					if (!empty($place)) {
						$data['publishedPlace'] = $place;
					}
					if (!empty($publisher)) {
						$data['publisher'] = $publisher;
					}
					if (!empty($year)) {
						$data['publishedYear'] = $year;
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
		foreach ($datafield->subfield AS $subfield) {
			$attributes = $subfield->attributes();
			if ((string) $attributes['code'] == $code) {
				return (string) $subfield;
			}
		}
	}

}
