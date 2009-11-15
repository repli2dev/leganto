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
	
	/**
	 * @return 
	 */
	public function testInsertBook(){
		// Autors
		$author1 = Leganto::authors()->createEmpty();
		$author1->firstname = "Firstname";
		$author1->lastname = "Lastname";
		$author1->type = AuthorEntity::PERSON;
		$author1->inserted = "now()";
		$author2 = Leganto::authors()->createEmpty();
		$author2->groupname = "Groupname";
		$author2->type = AuthorEntity::GROUP;
		$author2->inserted = "now()";
		// Tags
		$tag1 = Leganto::tags()->createEmpty();
		$tag1->name = "Tag";
		$tag1->languageId = self::LANGUAGEID;
		$tag2 = Leganto::tags()->createEmpty();
		$tag2->name = "Tag2";
		$tag2->languageId = self::LANGUAGEID;
		// Book
		$book = Leganto::books()->createEmpty();
		$book->title = "Title";
		$book->subtitle = "Subtitle";
		$book->inserted = "now()";
		$book->addAuthorToInsert($author1);
		$book->addAuthorToInsert($author2);
		$book->addTagToInsert($tag1);
		$book->addTagToInsert($tag2);
		$book->languageId = self::LANGUAGEID;
		// Insert
		$bookId = Leganto::books()->getInserter()->insert($book);
		// Tests
		if(empty($bookId)){
			$this->fail();
		}
		$insertedBook = Leganto::books()->getSelector()->find($bookId);
		if($insertedBook === NULL){
			$this->fail();
		}
		$this->assertEquals("Title", $insertedBook->title);
		$this->assertEquals("Subtitle", $insertedBook->subtitle);
		$this->assertEquals(1,$insertedBook->languageId);
		
		// Shoda autoru
		$rows = Leganto::authors()->getSelector()->findAllByBook($insertedBook);
		$authors = Leganto::authors()->fetchAndCreateAll($rows);
		$this->assertEquals(2,count($authors));
		foreach($authors as $insertedAuthor){
			if(!($insertedAuthor->equals($author1)) && !($insertedAuthor->equals($author2))){
				$this->fail();
			}
		}
		// Shoda tagu
		$rows = Leganto::tags()->getSelector()->findAllByBook($insertedBook);
		$tags = Leganto::tags()->fetchAndCreateAll($rows);
		$this->assertEquals(2,count($tags));
		foreach($tags as $insertedTag){
			if(!($insertedTag->equals($tag1)) && !($insertedTag->equals($tag2))){
				Debug::dump($insertedTag);
				$this->fail();
			}
		}
		
		// Pokus o vlozeni duplicitni knihy
		$this->assertEquals($bookId, Leganto::books()->getInserter()->insert($book));
		
		// Cleaning
		SimpleTableModel::createTableModel("book")->delete($insertedBook->bookNode);
		SimpleTableModel::createTableModel("author")->deleteAll(array("first_name" => "Firstname", "last_name" => "Lastname"));
		SimpleTableModel::createTableModel("tag")->deleteAll(array("name" => "Tag"));
	}
	
}
