<?php
class GoogleBooksBookFinderTest extends TestCase
{

	private $finder;

	protected function setUp() {
		$this->getContext()->resetDatabase();
		$this->finder = new GoogleBooksBookFinder("cs");
	}

	public function testGet() {
		$author = Leganto::authors()->createEmpty();
		$author->type = AuthorEntity::PERSON;
		$author->firstname = "Joanne";
		$author->lastname = "Rowling";
		$author->inserted = new DateTime();
		$author->persist();

		$book = Leganto::books()->createEmpty();
		$book->languageId = 1;
		$book->title	= "Harry Potter";
		$book->subtitle = "Kámen mudrců";
		$book->inserted = new DateTime();
		$book->persist();

		Leganto::books()->getUpdater()->setWrittenBy($book, $author);

		Debug::dump($this->finder->get($book));
	}

}
