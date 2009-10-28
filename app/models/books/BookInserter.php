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
class BookInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(IEntity $entity) {
		if(!($entity instanceof BookEntity)){
			throw new InvalidArgumentException("The entity is not the BookEntity");
		}
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// First I try to find the book with the same title (and subtitle)
		$source = Leganto::books()->getSelector()->findAll()
			->where("[title] = %s", $entity->title)
			->where("[id_language] = %s", $entity->languageId);
		if(isset($entity->subtitle)){
			$source->where("[subtitle] = %s",$entity->subtitle);
		}	
		$books = $source->fetchAll();
		// If there are same books check for duplicity
		if(!empty($books)){
			// Load book IDs
			$bookIds = array();
			foreach($books as $book){
				$bookIds[] = $book['id_book'];
			}
			// Load authors: id_book => id_author[]
			$bookAuthors = Leganto::authors()->getSelector()->findAllByBooks($bookIds)
				->fetchAssoc("id_book,id_author");
			// Foreach book I look at the array of authors
			foreach($bookAuthors as $bookId => $authors){
				$realBook = NULL;
				// FIXME: je naprosto korektni? Neexistuje kniha, u ktere by to selhalo?
				// If there is an intersect of authors, the books are same.
				foreach($authors as $author){
					foreach($entity->getAuthorsToInsert() as $newAuthor){
						if($newAuthor->equals(new AuthorEntity($author->getArrayCopy()))){
							$realBook = $bookId;
						} 
						break;
					}
					if(!empty($realBook)){
						break;
					}
				}
				if(!empty($realBook)){
					break;
				}
			}
		}
		
		// Save the general book entity and title
		if(empty($realBook)){
			$bookId = SimpleTableModel::createTableModel("book")->insert(array("inserted" => new DibiVariable("now()",'sql')));
			$entity->bookNode = $bookId;
			$bookTitleId = SimpleTableModel::createTableModel("book_title")->insert($this->getArrayFromEntity($entity, "Save"));
		} else {
			$bookId = $realBook;
			foreach($books as $book){
				if($book["id_book"] == $bookId && $book["title"] == $entity->title && (empty($entity->subtitle) || $book["subtitle"] == $entity->subtitle)){
					$bookTitleId = $book['id_book_title'];
					break;
				}
			}
			
		}
		// Save book author
		foreach ($entity->getAuthorsToInsert() AS $author) {
			$authodId = Leganto::authors()->getInserter()->insert($author);
			SimpleTableModel::createTableModel("written_by")->insert(array(
				"id_book"	=> $bookId,
				"id_author"	=> $authodId
			));
		}
		// Save tags
		foreach($entity->getTagsToInsert() AS $tag) {
			$tagId = Leganto::tags()->getInserter()->insert($tag);
			Leganto::tags()->getUpdater()->setTagged($bookId, $tagId);
		}
		
		return $bookTitleId;
		
	}
	
}