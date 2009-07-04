<?php
/**
 * The file archive where the icons of users are saved.
 *
 * @author Jan Papousek
 */
class IconFileArchive extends AFileArchive
{
	/**
	 * It returns absolute filesystem path to the archive directory.
	 *
	 * @return string
	 */
	public function getAbsolutePath() {
		return WWW_DIR . "/images/icons";
	}

	/**
	 * It returns array of files which are defaultly supported.
	 *
	 * @return FileTypeFilter
	 */
	protected function getFileTypeFilter() {
		return FileTypeFilter::crateWebImagesFilter();
	}

	/**
	 * It returns an URL of the archive,
	 * which the users can use to access through the web.
	 *
	 * @return string
	 */
	public function getURL()  {
		$domain = Site::getInstance()->getDomain();
		return $domain[Domain::DATA_URI] . "/images/icons";

	}
}

