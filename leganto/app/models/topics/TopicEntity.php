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

/**
 * @Id(translate=id_topic)
 */
class TopicEntity extends AEntity {

	protected $inserted;
	protected $name;
	/** @Translate(id_user) */
	protected $userId;
	/**
	 * @Skip(Save)
	 * @Translate(user_name)
	 */
	protected $userName;

	/**
	 * @Skip(Save)
	 * @Translate(last_post_inserted)
	 */
	protected $lastPostInserted;

	/**
	 * @Skip(Save)
	 * @Translate(number_of_posts)
	 */
	protected $numberOfPosts;

}
