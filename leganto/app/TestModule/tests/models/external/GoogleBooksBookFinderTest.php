<?php

class GoogleBooksBookFinderTest extends EskymoTestCase
{

	/** @var IEntity */
	private $entity;

	/** @var IFinder */
	private $finder;

	public function setUp() {
		$this->entity = Leganto::books()->getSelector()->find(1);
		$this->finder = new GoogleBooksBookFinder("cs");
	}

	public function testGetType() {
		$info = $this->finder->get($this->entity);
		$this->assertTrue(is_array($info));
	}

}
