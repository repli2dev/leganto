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

	protected $content;

	/**
	 * ID of the entity which is discussed
	 *
	 * @Translate(id_discussed)
	 */
	protected $discussed;

	/**
	 * ID of the discussion
	 *
	 * @Translate(id_discussion)
	 */
	protected $discussion;

	/**
	 * The name of the discussion
	 *
	 * @Translate(discussion_name)
	 * @Skip(Save)
	 */
	protected $discussionName;

	/**
	 * ID of the discussion type
	 *
	 * @Translate(id_discussable)
	 */
	protected $discussionType;

	protected $inserted;

	/**
	 * @Translate(id_language)
	 */
	protected $language;

	/**
	 * @Translate(locale)
	 * @Skip(Save)
	 */
	protected $locale;

	protected $reply;

	protected $subject;

	/**
	 * @Translate(id_user)
	 */
	protected $user;

	/**
	 * @Translate(user_nick)
	 * @Skip(Save)
	 */
	protected $userName;


}
