<?php

/**
 * Deleter which allows deleting of posts without reaction.
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Post;

use Leganto\ORM,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\Workers\IDeleter,
    Leganto\ORM\Exceptions\InvalidStateException,
    Leganto\ORM\SimpleTableModel,
    Leganto\DB\Factory;

class Deleter extends AWorker implements IDeleter {

	public function delete($id) {
		$post = Factory::post()->getSelector()->find($id);
		$discussion = Factory::discussion()->getSelector()->find($post->discussion);
		// Check if there are some replies
		$replies = SimpleTableModel::createTableModel("post", $this->connection)
			->findAll()
			->where("[reply] = %i", $post->getId())
			->count();
		if ($replies != 0) {
			throw new InvalidStateException("The post can not be deleted, because there are replies on it.");
		}
		// Delete the post
		SimpleTableModel::createTableModel("post", $this->connection)->delete($post->getId());
		// If the discussion contains only this post, delete it.
		if ($discussion->numberOfPosts == 1) {
			if ($discussion->discussionType == Selector::TOPIC) {
				$topic = Factory::topic()->getSelector()->find($discussion->discussed);
				$topic->delete();
			}
			$discussion->delete();
		}
	}

}
