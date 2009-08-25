<?php
/**
 * @author Jan Papousek
 */
class OpinionEntity extends AEntity
{

	/**
	 * @Required
	 * @Save(id_book)
	 * @Load(id_book)
	 */
	public $book;

	/**
	 * @Required
	 */
	public $content;

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Skip(Save)
	 * @Load(locale)
	 */
	public $language;

	/**
	 * @Required
	 * @Save(id_language)
	 * @Load(id_language)
	 */
	public $languageId;

	/**
	 * @Required
	 */
	public $rating;

	/**
	 * @Load(id_user)
	 * @Save(id_user)
	 * @Required
	 */
	public $userId;

	/**
	 * @Load(user_nick)
	 * @Skip(Save)
	 */
	public $userName;

	/* PROTECTED METHODS */

	protected function loadId(array $source) {
		$this->setId($source["id_opinion"]);
	}

}
