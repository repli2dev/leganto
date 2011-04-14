<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class FollowedSelector implements ISelector {

	public function find($id) {
		throw new NotImplementedException();
	}

	/**
	 * Find all
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_my_following]");
	}

	/**
	 * Find all followed users by book
	 * @param IEntity $user
	 * @return DibiDataSource
	 */
	public function findAllByBook($book) {
		if (empty($book)) {
			throw new NullPointerException("The parameter [book] is empty.");
		}
		return dibi::dataSource("SELECT [view_my_following].* FROM [view_my_following] INNER JOIN [following] ON [following].[id_user_followed] = [view_my_following].[id_user] WHERE [id_book_title] = %i AND [following].[id_user] = %i", $book,  System::user()->getId());
	}
	/**
	 * Find all opinion of followed users by book
	 * @param IEntity $user
	 * @return DibiDataSource
	 */
	public function findAllOpinionByBook($book) {
		if (empty($book)) {
			throw new NullPointerException("The parameter [book] is empty.");
		}
		return dibi::dataSource("SELECT [view_opinion].* FROM [view_opinion] INNER JOIN [following] ON [following].[id_user_followed] = [view_opinion].[id_user] WHERE [id_book_title] = %i AND [following].[id_user] = %i", $book,  System::user()->getId());
	}

}