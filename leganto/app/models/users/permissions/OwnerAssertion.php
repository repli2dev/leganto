<?php
class OwnerAssertion implements IPermissionAssertion
{

    public function assert(Permission $acl, $role, $resource, $privilege) {
	return $acl->getQueriedRole()->getId() == $acl->getQueriedResource()->getOwnerId();
    }

}

