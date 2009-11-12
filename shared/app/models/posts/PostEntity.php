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

/**
 * @Id(translate=id_post)
 */
class PostEntity extends AEntity
{

	/**
	 * @Required
	 */
	public $content;

	/**
	 * ID of the entity which is discussed
	 *
	 * @Required
	 * @Load(id_discussed)
	 */
	public $discussed;

	/**
	 * ID of the discussion
	 *
	 * @Load(id_discussion)
	 * @Save(id_discussion)
	 */
	public $discussion;

	/**
	 * The name of the discussion
	 *
	 * @Load(discussion_name)
	 * @Skip(Save)
	 */
	public $discussionName;

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
	 * @Required
	 * @Load(id_language)
	 * @Save(id_language)
	 */
	public $language;

	/**
	 * @Load(locale)
	 * @Skip(Save)
	 */
	public $locale;

	public $reply;

	/**
	 * @Required
	 */
	public $subject;

	/**
	 * @Required
	 * @Load(id_user)
	 * @Save(id_user)
	 */
	public $user;

	/**
	 * @Load(user_nick)
	 * @Skip(Save)
	 */
	public $userName;


}
