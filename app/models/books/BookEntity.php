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
class BookEntity extends AEntity
{

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Skip(Save)
	 * @Load(id_book)
	 */
	public $bookNode;

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
	public $inserted;

	/**
	 * @Skip(Save)
	 * @Load(number_of_opinions)
	 */
	public $numberOfOpinions;

	/**
	 * @Skip(Save)
	 */
	public $rating;

	public $subtitle;

	/**
	 * @Required
	 */
	public $title;

	public $updated;

	/* PUBLIC METHODS */

	public function addAuthor(AuthorEntity $author) {
		$this->authors[] = $author;
	}

	public function addTag(TagEntity $tag) {
		$this->tags[] = trim($tag);
	}

	public function isReadyToInsert() {
		foreach ($this->authors AS $author) {
			if (!$author->isReadyToInsert()) {
				return FALSE;
			}
		}
		foreach ($this->tags AS $tag) {
			if (!$tag->isReadyToInsert()) {
				return FALSE;
			}
		}
		return parent::isReadyToInsert() && !empty($this->authors) && !empty($this->tags);
	}

	/* PROTECTED METHODS */

	protected function loadId(array $source) {
		$this->setId($source["id_book_title"]);
	}

}
