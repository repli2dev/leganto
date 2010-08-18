<?php
class OwnerAssertion implements IPermissionAssertion
{

    public function assert(Permission $acl, $role, $resource, $privilege) {
	if (!($acl->getQueriedResource() instanceof Resource)) {
	    throw new InvalidArgumentException("The resource has to be instance of class [Resource], [".gettype($acl->getQueriedResource())."] given.");
	}
	return $acl->getQueriedRole()->getId() == $acl->getQueriedResource()->getOwnerId();
    }

}

