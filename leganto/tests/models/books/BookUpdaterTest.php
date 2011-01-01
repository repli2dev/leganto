<?php
class BookUpdaterTest extends TestCase
{

	private $author;

	protected function setUp() {
		$this->getContext()->resetDatabase();
		$this->author = Leganto::authors()->createEmpty();
		$this->author->type		= AuthorEntity::PERSON;
		$this->author->firstname= "Hugo";
		$this->auhtor->lastname	= "Kokoška";
		$this->author->inserted	= new DateTime();
		$this->author->persist();
	}

	public function testMerge() {
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

		Leganto::books()->getUpdater()->merge($books[0], $books[1]);

		$this->assertNull(Leganto::books()->getSelector()->find($books[1]->getId()));
		$this->assertEquals(0, $this->getContext()->getConnection()->query("SELECT * FROM [book] WHERE [id_book] = %i", $books[1]->bookNode)->count());
		// TODO: dodelat slozitejsi pripady a podrobne asserty

	}

}
