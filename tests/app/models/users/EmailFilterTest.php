<?php
/**
 * @author Jan Papousek
 */
class EmailFilterTest extends EskymoTestCase
{

	/**
	 * @var EmailFilter
	 */
	private $filter;

	public function  __construct() {
		parent::__construct();
		$this->filter = new EmailFilter();
	}

	protected function testCorrect() {
		$this->assertTrue($this->filter->accepts("aaa-aaa.7saaa.aaa@aaa.aa"));
	}

	/** @TestThrow(NullPointerException) */
	protected function testEmpty() {
		$this->filter->accepts(NULL);
	}

	protected function testWrong() {
		$this->assertFalse($this->filter->accepts("aaa.aaa.aaa"));
	}

}