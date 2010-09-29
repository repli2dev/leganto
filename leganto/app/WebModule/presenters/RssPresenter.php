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
class Web_RssPresenter extends Web_BasePresenter {
	const LIMIT = 10;

	public function renderDefault($user = NULL) {
		$source = Leganto::feed()->getSelector()->findAll();
		if (!empty($user)) {
			$userEntity = Leganto::users()->getSelector()->find($user);
			$users = Leganto::users()->getSelector()->findAllFollowed($userEntity)->fetchPairs("id_user", "id_user");
			$source->where("id_user IN %l", $users);
		}
		$source->applyLimit(self::LIMIT);
		foreach (Leganto::feed()->fetchAndCreateAll($source) AS $item) {
			if ($item->type == FeedItemEntity::TYPE_OPINION) {
				$this->addItem(
					System::translate("%s has an opinion on the book %s", $item->userNick, $item->categoryName),
					$this->link('//Book:default', $item->categoryId),
					$item->content,
					$item->inserted,
					$this->link('//Book:default', $item->categoryId)
				);
			} else {
				$this->addItem(
					System::translate("%s has contributed to the discussion %s", $item->userNick, $item->categoryName),
					$this->link('//Discussion:discussion', $item->categoryId),
					$item->content,
					$item->inserted,
					$this->link('//Discussion:discussion', $item->categoryId)
				);
			}
		}
	}

	protected function startUp() {
		parent::startUp();
		$this->getTemplate()->items = array();
		$this->getTemplate()->domain = System::domain();
	}

	private function addItem($title, $link, $description, $date, $guid) {
		$this->getTemplate()->items[] = new RssItem($title, $link, $description, $date, $guid);
	}

}

class RssItem {

	private $title;
	private $link;
	private $description;
	private $date;
	private $guid;

	public function __construct($title, $link, $description, $date, $guid) {
		$this->title = $title;
		$this->link = $link;
		$this->description = $description;
		$this->date = $date;
		$this->guid = $guid;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getLink() {
		return $this->link;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getDate() {
		return $this->date;
	}

	public function getGuid() {
		return $this->guid;
	}

}