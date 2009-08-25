<?php
/**
 * @author Jan Papousek
 */
interface IOpinionSelector extends ISelector
{

	/**
	 * @param BookEntity $book
	 * @return DibiDataSource
	 */
	function findAllByBook(BookEntity $book);

}
