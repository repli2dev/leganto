<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class DiscussionEntity extends AEntity
{

	/**
	 * ID of the entity which is discussed
	 *
	 * @Required
	 * @Load(id_discussed)
	 */
	public $discussed;

	/**
	 * ID of the discussion type
	 *
	 * @Required
	 * @Load(id_discussable)
	 */
	public $discussionType;

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Skip(Save)
	 * @Load(last_post_inserted)
	 */
	public $lastPostInserted;

	/**
	 * @Skip(Save)
	 */
	public $name;

	/**
	 * @Skip(Save)
	 * @Load(number_of_posts)
	 */
	public $numberOfPosts;

	/* PROTECTED METHODS */

	protected function loadId(array $source) {
		if (isset($source["id_discussion"])) {
			$this->setId($source["id_discussion"]);
		}
	}

}
