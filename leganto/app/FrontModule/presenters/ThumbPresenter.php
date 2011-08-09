<?php
/**
 * This presenter works with images
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
namespace FrontModule;
use Nette\Image;
class ThumbPresenter extends BasePresenter {

	/**
	 * This action resize image at given path and store it to cache
	 * @param string $path path to the image
	 * @param int $width Width of image
	 * @param int $height Height of image
	 */
	public function renderResize($path, $w = NULL, $h = NULL,$format = Image::JPEG) {
		error_reporting(0);
		if (empty($path)) {
			throw new \InvalidArgumentException("Specify path.");
		}

		// Strip WWW_DIR path from given path to ensure there won't be double same images
		$path = str_replace($this->getContext()->params["wwwDir"], '.', $path);
		$storageDir = $this->getContext()->params["tempDir"]."/images/";

		// Try to find file in cache
		if (file_exists($storageDir . md5($w . "_" . $h . "_" . $path . "_" . $format)) && filemtime($path) < filemtime(APP_DIR . "/temp/cache/" . md5($w . "_" . $h . "_" . $path . "_" . $format))) { // Entry found in cache
			$image = Image::fromFile($storageDir . md5($w . "_" . $h . "_" . $path . "_" . $format));
			$image->send();
		} else { // Not found in cache
			$image = Image::fromFile($path);
			if (!empty($w) || !empty($h)) {
				$image->resize($w, $h);
			}
			// Format cannot be forced
			$image->save($storageDir . md5($w . "_" . $h . "_" . $path . "_" . $format), 90,$format);
			if($format == Image::JPEG) {
				$image->send($format,90);
			} else {
				$image->send($format);
			}
		}

		// Terminate, image is already sent
		$this->terminate();
	}

}
