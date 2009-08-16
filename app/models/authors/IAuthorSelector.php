<?php
/**
 * Interface which extends common ISelector
 * @author: Jan Drabek
 */
interface IAuthorSelector extends ISelector
{
	/** @return DataSource */
	public function findAllByBook(BookEntity $book);
}
