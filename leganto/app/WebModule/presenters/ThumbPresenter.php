<?php

/**
 * This presenter works with images
 */
class Web_ThumbPresenter extends Web_BasePresenter {

	/**
	 * This action resize image at given path and store it to cache
	 * @param string $path path to the image
	 * @param int $width Width of image
	 * @param int $height Height of image
	 */
	public function renderResize($path, $w = NULL, $h = NULL) {
		if (empty($path)) {
			throw new NullPointerException("Specify path.");
		}

		// Strip WWW_DIR path from given path to ensure there won't be double same images
		$path = str_replace(WWW_DIR,'.',$path);

		// Try to find file in cache
		if(file_exists(WWW_DIR . "/cache/".md5($w."_".$h."_".$path))){	// Entry found in cache
			$image = Image::fromFile(WWW_DIR . "/cache/".md5($w."_".$h."_".$path));
			$image->send();
		} else { // Not found in cache
			$image = Image::fromFile($path);
			if(!empty($w) || !empty($h)) {
				$image->resize($w,$h);
			}
			$image->save(WWW_DIR . "/cache/".md5($w."_".$h."_".$path),90,Image::JPEG);
			$image->send(Image::JPEG,90);
		}

		// Terminate, image is already sent
		$this->terminate();
	}

}