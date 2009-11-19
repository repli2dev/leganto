<?php

class EditionInserterTest extends EskymoTestCase
{

	/** @var IEntity */
	private $book;

	/** @var array */
	private $info;

	/** @var EditionInserter */
	private $inserter;

	/** @var array */
	private $inserted;

	protected function setUp() {
		$this->inserter	= Leganto::editions()->getInserter();
		$this->book		= Leganto::books()->getSelector()->find(1);
		$gb				= new GoogleBooksBookFinder("cs");
		$this->info		= $gb->get($this->book);
	}

	protected function tearDown() {
		if (!empty($this->inserted)) {
			foreach ($this->inserted AS $edition) {
				$edition->delete();
			}
		}
	}

	public function testInsertByGoogleBooksInfo() {
		$this->inserted = $this->inserter->insertByGoogleBooksInfo($this->book, $this->info);
		$this->assertFalse(empty($this->inserted));
		foreach ($this->inserted AS $edition) {
			$this->assertEquals(IEntity::STATE_PERSISTED, $edition->getState());
		}
	}

}
