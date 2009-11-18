<?php
class EditionImageFinderTest extends EskymoTestCase
{

	/** @var IEntity */
	private $edition;

	/** @var IFinder */
	private $finder;

	protected function setUp() {
		$this->edition = SimpleEntityFactory::createEntityFactory("edition")->createEmpty();
		$this->edition->loadDataFromArray(array(
			"id_edition"	=> 1,
			"isbn"			=> "80-7197-191-X",
			"pages"			=> 200,
			"id_book"		=> 1
		));
		$this->finder = new EditionImageFinder();
	}

	public function testGet() {
		$output = $this->finder->get($this->edition);
		$this->assertFalse(empty($output));
	}

}
