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
			"id_edition"	=> 1,
			"isbn"			=> "80-7197-191-X",
			"pages"			=> 200,
			"id_book"		=> 1
		), "Load");
		$this->finder = new EditionImageFinder();
		$this->storage = new EditionImageStorage();
	}

	public function testStore() {
		$file = new File(ExtraArray::firstValue($this->finder->get($this->edition)));
		$file = $this->storage->store($this->edition, $file);
		if ($file)
		$this->assertFalse(empty($file));
		$this->assertEquals("File", $file->getClass());
		$this->assertTrue($file->exists());
		$directory = $file->getParentFile();
		$file->delete();
		$directory->delete();
	}

}

