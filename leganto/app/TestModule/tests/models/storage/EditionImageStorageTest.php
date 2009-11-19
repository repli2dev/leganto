<?php

class EditionImageStorageTest extends EskymoTestCase
{

	/** @var IEntity */
	private $edition;

	/** @var IFinder */
	private $finder;

	/** @var IStorage */
	private $storage;

	protected function setUp() {
		$this->edition = SimpleEntityFactory::createEntityFactory("edition")->createEmpty();
		$this->edition->loadDataFromArray(array(
			"isbn"				=> "978-80-7193-257-4",
			"pages"				=> 200,
			"id_book_title"		=> 1,
			"inserted"			=> new DibiVariable("now()", "sql")
		), "Load");
		$this->edition->persist();
		$this->finder = new EditionImageFinder();
		$this->storage = new EditionImageStorage();
	}

	protected function tearDown() {
		//$this->edition->delete();
	}

	public function testStore() {
		$file = new File(ExtraArray::firstValue($this->finder->get($this->edition)));
		$file = $this->storage->store($this->edition, $file);
		$this->assertFalse(empty($file));
		$this->assertEquals("File", $file->getClass());
		$this->assertTrue($file->exists());
		$directory = $file->getParentFile();
		$file->delete();
		$directory->delete();
	}

}

