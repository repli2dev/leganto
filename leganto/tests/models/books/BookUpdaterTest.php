<?php
class BookUpdaterTest extends TestCase
{

	private $author;

	private $user;

	protected function setUp() {
		$this->getContext()->resetDatabase();

		$this->author = Leganto::authors()->createEmpty();
		$this->author->type		= AuthorEntity::PERSON;
		$this->author->firstname= "Hugo";
		$this->auhtor->lastname	= "Kokoška";
		$this->author->inserted	= new DateTime();
		$this->author->persist();

		$this->user = Leganto::users()->createEmpty();
		$this->user->idLanguage = 1;
		$this->user->nickname	= "Někdo";
		$this->user->password	= "a;lkjdfvkldfjvldkv";
		$this->user->inserted	= new DateTime();
		$this->user->role		= UserEntity::COMMON;
		$this->user->persist();
	}

	public function testMergeWithOneNode() {
		$books = array(
			Leganto::books()->createEmpty(),
			Leganto::books()->createEmpty()
		);

		foreach ($books AS $book) {
			$book->title = "Nějaká kniha";
			$book->inserted	= new DateTime();
			$book->languageId = 1;
			$book->persist();
			Leganto::books()->getUpdater()->setWrittenBy($book, $this->author);
		}

		$books[0]->persist();
		$books[1]->bookNode = $books[0]->bookNode;
		$books[1]->persist();

		Leganto::books()->getUpdater()->merge($books[0], $books[1]);

		$this->assertNull(Leganto::books()->getSelector()->find($books[1]->getId()));
		$this->assertEquals($books[1]->bookNode, Leganto::books()->getSelector()->find($books[0]->getId())->bookNode);
	}

	public function testMergeSimple() {
		// persist books
		$books = array(
			Leganto::books()->createEmpty(),
			Leganto::books()->createEmpty()
		);

		foreach ($books AS $book) {
			$book->title = "Nějaká kniha";
			$book->inserted	= new DateTime();
			$book->languageId = 1;
			$book->persist();
			Leganto::books()->getUpdater()->setWrittenBy($book, $this->author);
		}

		// set tagged
		$tags = array(
			Leganto::tags()->createEmpty(),
			Leganto::tags()->createEmpty(),
		);

		for($i=0; $i<2; $i++) {
			$tags[$i]->languageId	= 1;
			$tags[$i]->name			= "word".$i;
			$tags[$i]->persist();
		}

		for($i=0; $i<2; $i++) {
			Leganto::books()->getUpdater()->setTagged($books[$i], $tags[$i]);
		}

		// set opinion
		$opinion = Leganto::opinions()->createEmpty();
		$opinion->languageId	= 1;
		$opinion->rating		= 1;
		$opinion->content		= "1";
		$opinion->userId		= $this->user->getId();
		$opinion->bookTitleId	= $books[1]->getId();
		$opinion->inserted		= new DateTime();
		$opinion->persist();

		// insert to a shelf
		$shelf = Leganto::shelves()->createEmpty();
		$shelf->inserted		= new DateTime();
		$shelf->user			= $this->user->getId();
		$shelf->type			= "general";
		$shelf->name			= "shelf";
		$shelf->persist();

		Leganto::shelves()->getUpdater()->insertToShelf($shelf, $books[1]);

		Leganto::books()->getUpdater()->merge($books[0], $books[1]);

		$this->assertNull(Leganto::books()->getSelector()->find($books[1]->getId()));
		$this->assertEquals(0, $this->getContext()->getConnection()->query("SELECT * FROM [book] WHERE [id_book] = %i", $books[1]->bookNode)->count());
		$this->assertEquals(2, dibi::query("SELECT [id_book] FROM [tagged] WHERE [id_book] = %i", $books[0]->bookNode)->count());
		$this->assertEquals(1, dibi::query("SELECT [id_book_title] FROM [opinion] WHERE [id_book_title] = %i", $books[0]->getId())->count());
		$this->assertEquals(1, dibi::query("SELECT [id_book_title] FROM [in_shelf] WHERE [id_book_title] = %i", $books[0]->getId())->count());
	}

}