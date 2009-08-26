<?php
/**
 * @author Jan Papousek
 */
class BookInserterTest extends EskymoTestCase
{
	const LANGUAGEID = 1;
	/**
	 * @TestThrow(InvalidArgumentException)
	 * @return 
	 */
	public function testInsertNotReady(){
		Leganto::books()->getInserter()->insert(Leganto::books()->createEmpty());
	}
	
	public function testInsertBook(){
		$author = Leganto::authors()->createEmpty();
		$author->firstname = "Firstname";
		$author->lastname = "Lastname";
		$author->type = AuthorEntity::PERSON;
		$tag = Leganto::tags()->createEmpty();
		$tag->name = "Tag";
		$tag->languageId = self::LANGUAGEID;
		$book = Leganto::books()->createEmpty();
		$book->title = "Title";
		$book->subtitle = "Subtitle";
		$book->addAuthorToInsert($author);
		$book->addTagToInsert($tag);
		$book->languageId = self::LANGUAGEID;
		$bookId = Leganto::books()->getInserter()->insert($book);
		if(empty($bookId)){
			$this->fail();
		}
		$book = Leganto::books()->getSelector->find($bookId);
		if($book === NULL){
			$this->fail();
		}
		$this->assertEquals("Ttile", $book->title);
		$this->assertEquals("Subtitle", $book->subtitle);
		//$this->assertEquals("", $actual)
		$authors = Leganto::authors()->getSelector()->findAllByBook($book);
		
	}
	
	public function testInsertDuplicateBook(){
		
	}
	
	public function testInsertBookMultipleAuthors(){
		
	}
	
	public function testInsertDuplicateBookMultipleAuthors(){
		
	}
}
