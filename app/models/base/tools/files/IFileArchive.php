<?php
/**
 * This interface is designed to be implemented by classes
 * which represents file archives. They provide manipulation with files
 * such as uploading, deleting, browsing ...
 *
 * @author Jan Papousek
 */
interface IFileArchive
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
	 * It deletes files which are accepted by the filter.
	 *
	 * @param IFileFilter $filter The file filter.
	 * @throws NullPointerException if the $filter is empty.
	 * @throws IOException if there is an I/O problem.
	 */
	function delete(IFileFilter $filter);

	/**
	 * It returns absolute filesystem path to the archive directory.
	 *
	 * @return string
	 */
	function getAbsolutePath();

	/**
	 * It returns an URL of the archive,
	 * which the users can use to access through the web.
	 *
	 * @return string
	 */
	function getURL();

	/**
	 * It uploades a file on the server. If there is nonempty attribute $prefix,
	 * the file will be saved with prefix.
	 *
	 * @param HttpUploadedFile $file The file will be uploaded.
	 * @param string $prefix The prefix.
	 * @throws NullPointerException if the $file is empty
	 * @throws IOException if there is an I/O problem with uploading.
	 */
	function upload(HttpUploadedFile $file, $prefix = NULL);

	/**
	 * It returns the upladed files which are accepted by the filter.
	 *
	 * @param IFileFilter $filter The filter
	 * @return array|File Uploaded files.
	 * @throws IOException if there is an I/O problem.
	 */
	function view(IFileFilter $filter);
}
