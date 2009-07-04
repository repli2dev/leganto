<?php
// TODO: Create other methods such as methods in class File in Java 6 api
// TODO: Write a method 'getParentFile()'

/**
 * The file descriptor
 *
 * @author Jan Papousek
 */
class File extends Object
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
	 * It creates new file descriptor
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
	 *
	 */
	public function createNewFile() {
		if ($this->exists()) {
			return;
		}
		// FIXME: Check, if it works.
		$file = fopen($this->getPath(), "w+");
		fclose($file);
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
			if ($dirname != $this->path) {
				$this->parent = new File($dirname);
			}
			// TODO: Make other possibilities.
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
}

