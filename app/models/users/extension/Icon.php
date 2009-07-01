<?php
/**
 * This class provides working with icons of users.
 *
 * @author Jan Papousek
 */
class Icon
{

	/**
	 * The error code for the invalid uploaded file.
	 */
	const ERROR_INVALID_FILE = 2;

	/**
	 * The error code for the not supported file type.
	 */
	const ERROR_NOT_SUPPORTED_FILE_TYPE = 1;

	/**
	 * The error code for the error in the file saving.
	 */
	const ERROR_SAVING = 3;

	/**
	 * The file name of the default icon.
	 *
	 * @var string
	 */
	const DEFAULT_ICON = "default.png";

	/**
	 * It deletes user's icon.
	 *
	 * @param int $user User's ID.
	 * @throws NullPointerException if the $user is empty.
	 */
	public static function delete($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		foreach (glob(self::getDirToIcons() . "/$user.*") AS $file) {
			@unlink($file);
		}
	}

	/**
	 * It returns the absolute filesystem path to the directory
	 * where the icons are saved.
	 *
	 * @return string
	 */
	public static function getDirToIcons() {
		return WWW_DIR . "/images/icons";
	}

	/**
	 * It returns an URL to user's icon.
	 *
	 * If the user has no icon, it returns an URL to the default icon.
	 *
	 * @param int $user User's ID.
	 * @return string
	 * @throws NullPointerException if the $user is empty.
	 */
	public static function getIconURL($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		foreach (glob(self::getDirToIcons() . "/$user.*") AS $file) {
			$imageSize = getimagesize($file);
			return self::getURLToIcons() . "/" . $user . "." . $imageSize[2];
		}
		return self::getURLToIcons() . "/" . self::DEFAULT_ICON;
	}

	/**
	 * It returns the URL of the directory where the icons are saved.
	 *
	 * @return string
	 */
	public static function getURLToIcons() {
		$domain = Site::getInstance()->getDomain();
		return $domain[Domain::DATA_URI] . "/images/icons";
	}

	/**
	 * It loads an icon to the user's profile.
	 *
	 * @param int $user User's ID.
	 * @param HttpUploadedFile $file Uploaded icon.
	 * @throws NullPointerException if the $user or $file is empty.
	 * @throws DataNotFoundException if the user does not exist.
	 * @throws IOException if there is an I/O problem with icon.
	 */
	public static function loadIcon($user, HttpUploadedFile $file) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		if (empty($file)) {
			throw new NullPointerException("file");
		}
		$users = new Users();
		if ($users->get()->where("%n = %i",Users::DATA_ID, $user)->count() == 0) {
			throw new DataNotFoundException("user");
		}
		if (!$file->isOK()) {
			throw new IOException("file", self::ERROR_INVALID_FILE);
		}
		// Image type
		$imageSize = getimagesize($file->getTemporaryFile());
	        switch($imageSize[2]) {
            default:
                throw new IOException("Not supported file type: $imageSize[2].", self::ERROR_NOT_SUPPORTED_FILE_TYPE);
                break;
            case IMAGETYPE_BMP:
				$end = "bmp";
				break;
            case IMAGETYPE_JPEG:
				$end = "jpeg";
				break;
            case IMAGETYPE_GIF:
				$end = "gif";
				break;
            case IMAGETYPE_PNG:
				$end = "png";
				break;
        }
		self::delete($user);
		$newFile = self::getDirToIcons() . "/$user.$end";
		if (!$file->move($newFile)) {
			throw new IOException("There is a problem to save file '$newFile'.", self::ERROR_SAVING);
		}
	}

}