<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class BookSelector implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_book]");
	}
	
	public function findAllByAuthor(AuthorEntity $author) {
		if (empty($author)) {
			throw new NullPointerException("author");
		}
		if ($author->getId() == NULL) {
			throw new NullPointerException("author::id");
		}
		return dibi::dataSource("SELECT * FROM [view_author_book] WHERE [id_author] = %i", $author->getId())
				->orderBy(array("id_book","title"));
	}

	public function findAllByShelf(ShelfEntity $shelf) {
		if (empty($shelf)) {
			throw new NullPointerException("shelf");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_shelf] = %i", $shelf->getId());
	}

	public function findAllSimilar(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		if (empty($book->bookNode)) {
			throw new NullPointerException("book::bookNode");
		}
		return dibi::dataSource("SELECT * FROM [view_similar_book] WHERE [id_book_from] = %i", $book->bookNode);
	}
	
	public function search($keyword){
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$source = dibi::dataSource("SELECT * FROM [view_book_search]");
		$keywords = preg_split('/ /', $keyword);
		foreach($keywords AS $word) {
		    $word = "%".$word."%";
		    $source->where("
			[title] LIKE %s", $word," OR
			[subtitle] LIKE %s", $word," OR
			[name] LIKE %s", $word," OR
			[first_name] LIKE %s", $word," OR
			[last_name] LIKE %s", $word," OR
			[group_name] LIKE %s", $word
		    );
		}
		return $source;
	}

	public function findOthers(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return $this->findAll()
			->where("[id_book] = %i", $book->bookNode)
			->where("[id_book_title] != %i", $book->getId())
			->orderBy("title");

	}

	/** @return BookEntity */
	public function find($id) {
		return Leganto::books()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_book] WHERE [id_book_title] = %i", $id)
			);
	}

}