<?php
/**
 * Reader's book
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        http://code.google.com/p/preader/
 * @category    Reader
 * @package     Reader\Base\Files
 * @version     2009-07-04
 */

/*namespace Reader\Base\Files;*/

/**
 * The file type filter.
 *
 * @author      Jan Papousek
 * @version     2009-07-04
 * @package     Reader\Base\Files
 * @see         FileType
 */
class FileTypeFilter extends /*Object\*/Object
{

	/**
	 * The filter types.
	 *
	 * @var array|int
	 */
	private $types;

	/**
	 * It creates nre file type filter.
	 *
	 * @param array|int $types The supported types.
	 */
	public function  __construct(array $types) {
		$this->types = $types;
	}

	/**
	 * Factory method which creates new filter
	 * containing types of images used on web.
	 *
	 * @return array|int
	 */
	public static function crateWebImagesFilter() {
		return new FileTypeFilter(array(
			FileType::BMP,
			FileType::GIF,
			FileType::JPEG,
			FileType::PNG
		));
	}

	/**
	 * It checks if the file has supported file type.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	public function accepts(File $file) {
		$type = $file->getType();
		foreach ($this->types AS $typeCode) {
			if ($typeCode == $type->getTypeCode()) {
				return TRUE;
			}
		}
		return FALSE;
	}

}
