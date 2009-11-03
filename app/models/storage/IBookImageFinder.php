<?php
interface IBookImageFinder
{

	/**
	 * It tries to return the cover image of the book
	 *
	 * @param BookEntity $book
	 * @return File or NULL if the image is not avaiable
	 */
	function getImage(BookEntity $book);

}
