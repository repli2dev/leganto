<?php
/**
 * Interface which extends common ISelector
 * @author: Jan Drabek
 */
interface ITagSelector extends ISelector
{
	/** @return DataSource */
	public function findAllByBook(BookEntity $book);
	
	public function setTagged($book, $tag);
}
