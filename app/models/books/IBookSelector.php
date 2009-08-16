<?php
/**
 * Interface which extends common ISelector
 * @author: Jan Drabek
 */
interface IBookSelector extends ISelector
{
	
	public function findAllByAuthor(IEntity $author);
	
	public function findOthers(BookEntity $book);
	
}
