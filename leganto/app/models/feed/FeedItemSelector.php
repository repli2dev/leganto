<?php
class FeedItemSelector implements ISelector
{

    public function find($id) {
	throw new NotSupportedException("The feed items do not have IDs.");
    }

    public function findAll() {
	return dibi::dataSource("SELECT * FROM [tmp_feed]");
    }

    public function findAllByUser(IEntity $user) {
	if (empty($user)) {
	    throw new NullPointerException("The parameter [user] is empty.");
	}
	if ($user->getId() == NULL) {
	    throw new NullPointerException("The user has no ID.");
	}
	return dibi::dataSource("SELECT * FROM [tmp_feed] WHERE [id_user] = %i", $user->getId());
    }

}