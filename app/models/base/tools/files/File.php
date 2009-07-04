<?php
// TODO: Create other methods such as methods in class File in Java 6 api
// TOD: Tests

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
 * The file descriptor, which provides all manipulation with files.
 *
 * @author      Jan Papousek
 * @version     2009-07-04
 * @package     Reader\Base\Files
 */
class File extends /*Nette\*/Object
{

	/**
	 * The error code for IOException if there is a security problem.
	 *
	 * @var int
	 */
	const ERROR_SECURITY = 10;

	/**
	 * The error code for IOException if there is a problem to detect file info
	 *
	 * @var int
	 */
	const ERROR_FILE_INFO = 20;

	/**
	 * The file path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * The file name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The parent file.
	 *
	 * @var File
	 */
	 private $parent;

	 /**
	  * The file type.
	  *
	  * @var FileType
	  */
	private $type;

	/**
	 * It creates new file descriptor.
	 *
	 * @param string $path The asbtract path to the file.
	 * @throws NullPointerException if the $path is empty.
	 */
	public function  __construct($path) {
		if (empty($path)) {
			throw new NullPointerException("path");
		}
		$this->path = $path;
	}

	/**
	 * It checks if the file is executable.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function canExecute() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		return is_executable($this->getPath());
	}

	/**
	 * It checks if the file is readable.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function canRead() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		return is_readable($this->getPath());
	}

	/**
	 * It checks if the file is writable.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function canWrite() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		return is_writable($this->getPath());
	}

	/**
	 * Atomically creates a new,empty file named by this abstract pathname
	 * if and only if a file with this name does not yet exist.
	 *
	 * @return boolean It returns TRUE, if the file does not exist
	 *		and was successfully created, otherwise FALSE.
	 * @throws IOException if there is an I/O problem.
	 */
	public function createNewFile() {
		if ($this->exists()) {
			return FALSE;
		}
		if ($this->getParentFile() != NULL && !$this->getParentFile()->canWrite()) {
			throw new IOException("The file can not be created.", self::ERROR_SECURITY);
		}
		$file = fopen($this->getPath(), "w+");
		fclose($file);
		return TRUE;
	}

	/**
	 * It deletes the file.
	 *
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is an I/O problem.
	 */
	public function delete() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		$parent = $this->getParentFile();
		if (!empty($parent) && !$parent->canWrite()) {
			throw new IOException("The file cannot be deleted.", self::ERROR_SECURITY);
		}
		unlink($this->getPath());
	}

	/**
	 * It checks the file existence
	 *
	 * @return boolean It returns TRUE, if the file exists, otherwise FALSE.
	 */
	public function exists() {
		return file_exists($this->getPath());
	}

	/**
	 * It returns abosolute file system path to the file.
	 *
	 * @return string
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function getAbsolutePath() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		return realpath($this->getPath());
	}

	/**
	 * It returns file name.
	 *
	 * @return string
	 */
	public function getName() {
		if (empty($this->name)) {
			$this->name = basename($this->path);
		}
		return $this->name;
	}

	/**
	 * Returns the abstract pathname of this abstract pathname's parent,
	 * or null if this pathname does not name a parent directory.
	 *
	 * @return File
	 */
	public function getParentFile() {
		if (empty($this->parent)) {
			$dirname = dirname($this->path);
			// FIXME: UNIX dependent
			if ($dirname != $this->path && $dirname != "/") {
				$this->parent = new File($dirname);
			}
		}
		return $this->parent;
	}

	/**
	 * It returns file path
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * It returns file size id bytes.
	 *
	 * @return int
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function getSize() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		return filesize($this->path);
	}

	/**
	 * It returns file type.
	 *
	 * @return FileType
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function getType() {
		if (empty($this->type)) {
			if (!$this->exists()) {
				throw new FileNotFoundException($this->path);
			}
			if (class_exists("finfo")) {
				$finfo = new finfo(FILEINFO_MIME,$this->getPath());
				if (!$fi) {
					throw new IOException("There is a problem to detect file type.", self::ERROR_FILE_INFO);
				}
				$mimeType = $info->file($this->getPath());
				$finfo->close();
			}
			else {
				$mimeType = mime_content_type($this->getPath());
			}
			$this->type = new FileType($mimeType);
		}
		return $this->type;
	}

	/**
	 * It checks if the file is directory.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function isDirectory() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		return is_dir($this->getPath());
	}

	/**
	 * It checks if the file is file (not directory).
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function isFile() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		return is_file($this->getPath());
	}

	public function __toString() {
		return $this->getPath();
	}

	/**
	 * It returns files located in this directory.
	 *
	 * @param IFileFilter $filter The file filter.
	 * @return array|File
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws NotSupportedException if this file is not a directory.
	 */
	public function listFiles(IFileFilter $filter = NULL) {
		if (!empty($filter) && ($filter instanceof FileNameFilter)) {
			$fileNameFilter = $filter;
		}
		else {
			$fileNameFilter = NULL;
		}
		$result = array();
		$files = $this->listPaths($fileNameFilter);
		foreach ($files AS $filename) {
			$file = new File($filename);
			if (!empty($filter) && !($filter instanceof FileNameFilter)) {
				if (!$filter->accepts($file)) {
					continue;
				}
			}
			$result[] = $file;
		}
		return $result;
	}

	/**
	 * It returns paths to the files located in this directory.
	 *
	 * @param FileNameFilter $filter The file name filter.
	 * @return array|string
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws NotSupportedException if this file is not a directory.
	 */
	public function listPaths(FileNameFilter $filter = NULL) {
		if (!$this->isDirectory()) {
			throw new NotSupportedException();
		}
		if (!empty ($filter)) {
			$rule = $filter->getRule();
		}
		else {
			$rule = "*";
		}
		return glob($this->getPath() . "/" . $rule);
	}

	/**
	 * It makes a directory describing by this abstract path, if it does not exist.
	 *
	 * @return boolean It returns TRUE, if the directory was created, otherwise FALSE.
	 * @throws IOException if there is a problem to create directory.
	 */
	public function mkdir() {
		if ($this->exists()) {
			return FALSE;
		}
		if ($this->getParentFile() != NULL && !$this->getParentFile()->canWrite()) {
			throw new IOException("The directory can not be created.", self::ERROR_SECURITY);
		}
		mkdir($this->getPath(), "0777");
		return TRUE;
	}

	/**
	 * It makes a directory and all parent directories which do not already exist.
	 *
	 * @return boolean It returns TRUE, if the directory was created, otherwise FALSE.
	 * @throws IOException if there is a problem to create directory.
	 */
	public function mkdirs() {
		if ($this->exists()) {
			return FALSE;
		}
		if ($this->parent != NULL && !$this->parent->canWrite()) {
			throw new IOException("The directory can not be created.", self::ERROR_SECURITY);
		}
		mkdir($this->getPath(), "0777", TRUE);
		return TRUE;
	}
}

