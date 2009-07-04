<?php
/**
 * This abstract class is designed to be extended by classes
 * representing an file archive. This is the base implementation
 * of IFileArchive interface.
 *
 * @author Jan Papousek
 * @see IFileArchive
 */
abstract class AFileArchive extends Object implements IFileArchive
{

	/**
	 * It deletes files which are accepted by the filter.
	 *
	 * @param IFileFilter $filter The file filter.
	 * @throws NullPointerException if the $filter is empty.
	 * @throws IOException if there is an I/O problem.
	 */
    public function delete(IFileFilter $filter) {
		if (empty($filter)) {
			throw new NullPointerException("filter");
		}
		if ($filter instanceof FileNameFilter) {
			$rule = $filter->getRule();
		}
		else {
			$rule = "*";
		}
		foreach (glob($this->getAbsolutePath() . "/" . $rule) AS $filename) {
			$file = new File($filename);
			if ($filter->accepts($file)) {
				$file->delete();
			}
		}
	}

	/**
	 * It returns array of files which are defaultly supported.
	 *
	 * @return FileTypeFilter
	 */
	abstract protected function getFileTypeFilter();

	/**
	 * It uploades a file on the server. If there is nonempty attribute $prefix,
	 * the file will be saved with prefix.
	 *
	 * @param HttpUploadedFile $file The file will be uploaded.
	 * @param string $prefix The prefix.
	 * @throws NullPointerException if the $file is empty
	 * @throws IOException if there is an I/O problem with uploading.
	 */
	public function upload(HttpUploadedFile $file, $prefix = NULL) {
		if (empty ($file)) {
			throw new NullPointerException("file");
		}
		if (!$file->isOK()) {
			throw new IOException("The file is invalid.", IFileArchive::ERROR_INVALID_FILE);
		}
		$newFile = new File($file->getTemporaryFile());
		if (!$this->getFileTypeFilter()->accepts($newFile)) {
			throw new IOException("The file type is not supported.", IFileArchive::ERROR_NOT_SUPPORTED_FILE_TYPE);
		}
		$name = $file->getName();
		if (!empty($prefix)) {
			$name = $prefix . $name;
		}
		if (!$file->move($this->getAbsolutePath() . "/" . $name)) {
			throw new IOException("There is a problem to upload the file.", IFileArchive::ERROR_SAVING);
		}
	}

	/**
	 * It returns the upladed files which are accepted by the filter.
	 *
	 * @param IFileFilter $filter The filter
	 * @return array|File Uploaded files.
	 * @throws IOException if there is an I/O problem.
	 */
	public function view(IFileFilter $filter) {
		if (empty($filter)) {
			throw new NullPointerException("filter");
		}
		if ($filter instanceof FileNameFilter) {
			$rule = $filter->getRule();
		}
		else {
			$rule = "*";
		}
		$result = array();
		foreach (glob($this->getAbsolutePath() . "/" . $rule) AS $filename) {
			$file = new File($filename);
			if ($filter->accepts($file)) {
				$result[] = $file;
			}
		}
		return $result;
	}
}
