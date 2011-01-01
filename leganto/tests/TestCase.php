<?php
abstract class TestCase extends PHPUnit_Framework_TestCase
{

	private $context;

	/**
	 * @return TestContext
	 */
	protected final function getContext() {
		if (!isset($this->context)) {
			$this->context = new TestContext(dibi::getConnection());
		}
		return $this->context;
	}


}