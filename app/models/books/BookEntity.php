<?php
/**
 * @author Jan Papousek
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

	protected function loadIdFromRow(DibiRow $row) {
		$this->setId($row["id_book_title"]);
	}

}
