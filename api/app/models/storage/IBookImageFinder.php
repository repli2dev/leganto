<?php
interface IBookImageFinder
{

	/**
	 * It tries to return the cover image of the book
	 *
	 * @param BookEntity $book
	 * @return File or NULL if the image is not available
	 */
	function getImage(BookEntity $book);

}
