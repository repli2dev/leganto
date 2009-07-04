<?php
/**
 * This class provides working with icons of users.
 *
 * @author Jan Papousek
 */
class Icon
{

	/**
	 * The archive where the icons are situated.
	 * 
	 * @var IFileArchive
	 */
	private static $archive;

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
		$filter = new FileNameFilter($user . "_*");
		self::getArchive()->delete($filter);
	}

	/**
	 * It returns used file archive.
	 *
	 * @return IFileArchive
	 */
	private static function getArchive() {
		if (empty(self::$archive)) {
			self::$archive = new IconFileArchive();
		}
		return self::$archive;
	}

	/**
	 * It returns the absolute filesystem path to the directory
	 * where the icons are saved.
	 *
	 * @return string
	 */
	public static function getDirToIcons() {
		return self::getArchive()->getAbsolutePath();
	}

	/**
	 * It returns an user's icon file.
	 *
	 * If the user has no icon, it returns a default icon file.
	 *
	 * @param int $user User's ID.
	 * @return File
	 * @throws NullPointerException if the $user is empty.
	 */
	public static function getIcon($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		$filter = new FileNameFilter($user . "_*");
		$files = self::getArchive()->view($filter);
		if (!empty($files)) {
			return $files[0];
		}
		else {
			return new File(self::getDirToIcons() . "/" .self::DEFAULT_ICON);
		}
	}

	/**
	 * It returns the URL of the directory where the icons are saved.
	 *
	 * @return string
	 */
	public static function getURLToIcons() {
		self::getArchive()->getURL();
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
		self::delete($user);
		self::getArchive()->upload($file, $user . "_");
	}

}