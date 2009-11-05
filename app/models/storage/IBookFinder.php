<?php
interface IBookFinder
{

	/**
	 * It tries to return the ISBN of the book
	 *
	 * @param BookEntity $book
	 * @return int or NULL
	 */
	function getISBN(BookEntity $book);

}
