<?php
/**
 * Deleter which allows deleting of posts without reaction.
 *
 * @author Jan Papousek
 */
class PostDeleter implements IDeleter
{

	public function delete($id) {
		$post = Leganto::posts()->getSelector()->find($id);
		// Check if there are some replies
		$replies = SimpleTableModel::createTableModel("post")
			->findAll()
			->where("[reply] = %i", $post->getId())
			->count();
		if ($replies != 0) {
			throw new InvalidStateException("The post can not be deleted, because there are replies on it.");
		}
		// Delete the post
		SimpleTableModel::createTableModel("post")->delete($post->getId());
		// If the discussion is empty, delete it.
		$otherPostsInDiscussion = SimpleTableModel::createTableModel("post")
			->findAll()
			->where("[id_discussion] = %i", $post->discussion )
			->count();
		if ($otherPostsInDiscussion == 0) {
			SimpleTableModel::createTableModel("discussion")->delete($post->discussion);
		}
	}


}
