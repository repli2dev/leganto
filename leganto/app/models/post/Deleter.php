<?php

/**
 * Deleter which allows deleting of posts without reaction.
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
namespace Leganto\DB\Post;
use	Leganto\ORM,
	Leganto\ORM\Workers,
	Leganto\ORM\Exceptions;

class Deleter implements IDeleter {

	public function delete($id) {
		$post = Leganto::posts()->getSelector()->find($id);
		$discussion = Leganto::discussions()->getSelector()->find($post->discussion);
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
		// If the discussion contains only this post, delete it.
		if ($discussion->numberOfPosts == 1) {
			if ($discussion->discussionType == PostSelector::TOPIC) {
				$topic = Leganto::topics()->getSelector()->find($discussion->discussed);
				$topic->delete();
			}
			$discussion->delete();
		}
	}

}
