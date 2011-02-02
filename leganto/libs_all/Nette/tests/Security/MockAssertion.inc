<?php

/**
 * Common code for Permission test cases.
 */



class MockAssertion implements IPermissionAssertion
{
	private $returnValue;


	public function __construct($returnValue)
	{
		$this->returnValue = (bool) $returnValue;
	}


	public function assert(Permission $acl, $roleId, $resourceId, $privilege)
	{
	return $this->returnValue;
	}

}