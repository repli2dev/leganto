<?php
/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

class OwnerAssertion implements IPermissionAssertion {

	public function assert(Permission $acl, $role, $resource, $privilege) {
		if (!($acl->getQueriedResource() instanceof Resource)) {
			throw new InvalidArgumentException("The resource has to be instance of class [Resource], [" . gettype($acl->getQueriedResource()) . "] given.");
		}
		return $acl->getQueriedRole()->getId() == $acl->getQueriedResource()->getOwnerId();
	}

}

