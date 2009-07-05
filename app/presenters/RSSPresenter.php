<?php
/**
 * This presenter provids manipulation with RSS channel.
 *
 * @author Jan Papousek
 */
abstract class RSSPresenter extends Presenter
{

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		$template->setFile(TEMPLATES_DIR . "/@rss.phtml");

		$template->items = array();

		return $template;
	}

	/**
	 * It adds an item to RSS channel.
	 *
	 * @param array|string $item The item containing keys: title, link, guid, description, category, comments, pubDate
	 * @throws NullPointerException if the $item or $item[title], $item[link], $item[description] are empty.
	 */
	public function addItem(array $item) {
		if (empty($item)) {
			throw new NullPointerException("item");
		}
		$required = array("title", "link", "description");
		foreach ($required AS $key) {
			if (empty($item[$key])) {
				throw new NullPointerException("item[$key]");
			}
		}
		$this->template->items[] = $item;
	}

	/**
	 * It sets RSS category.
	 *
	 * @param string $category The category.
	 * @throws NullPointerException if the $category is empty.
	 */
	protected function setCategory($category) {
		if (empty($category)) {
			throw new NullPointerException("category");
		}
		$this->template->category = $category;
	}

	/**
	 * It sets RSS copyright.
	 *
	 * @param string $copyright The copyright.
	 * @throws NullPointerException if the $copyright is empty.
	 */
	protected function setCopyright($copyright) {
		if (empty($copyright)) {
			throw new NullPointerException("copyright");
		}
		$this->template->copyright = $copyright;
	}

	/**
	 * It sets RSS description.
	 *
	 * @param string $description The description.
	 * @throws NullPointerException if the $description is empty.
	 */
	protected function setLanguage($description) {
		if (empty($description)) {
			throw new NullPointerException("description");
		}
		$this->template->description = $description;
	}

	/**
	 * It sets RSS docs.
	 *
	 * @param string $docs The docs .
	 * @throws NullPointerException if the $docs is empty.
	 */
	protected function setDocs($docs) {
		if (empty($docs)) {
			throw new NullPointerException("docs");
		}
		$this->template->docs = $docs;
	}

	/**
	 * It sets RSS language.
	 *
	 * @param string $language The language (locale).
	 * @throws NullPointerException if the $language is empty.
	 */
	protected function setLanguage($language) {
		if (empty($language)) {
			throw new NullPointerException("language");
		}
		$this->template->language = $language;
	}

	/**
	 * It sets RSS link.
	 *
	 * @param string $link The link.
	 * @throws NullPointerException if the $link is empty.
	 */
	protected function setLink($link) {
		if (empty($link)) {
			throw new NullPointerException("link");
		}
		$this->template->link = $link;
	}

	/**
	 * It sets RSS title.
	 *
	 * @param string $title The title.
	 * @throws NullPointerException if the $title is empty.
	 */
	protected function setTitle($title) {
		if (empty($title)) {
			throw new NullPointerException("title");
		}
		$this->template->title = $title;
	}

	/**
	 * It sets RSS subtitle.
	 *
	 * @param string $subtitle The subtitle.
	 * @throws NullPointerException if the $subtitle is empty.
	 */
	protected function setTitle($subtitle) {
		if (empty($subtitle)) {
			throw new NullPointerException("subtitle");
		}
		$this->template->subtitle = $subtitle;
	}

}
