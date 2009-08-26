<?php
/**
 * @author Jan Papousek
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

	protected function loadId(array $source) {
		$this->setId($source["id_post"]);
	}

}
