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
			// Only apply if there are any followed users
			if(count($users) > 0) {
				$source->where("id_user IN %l", $users);
			}
		}
		$source->applyLimit(self::LIMIT);
		foreach (Leganto::feed()->fetchAndCreateAll($source) AS $item) {
			$content = explode("#$#",$item->content);
			if ($item->type == FeedItemEntity::TYPE_NEW_OPINION) {
				$this->addItem(
					System::translate("%s have just contributed new opinion on book %s", $item->userNick, $content[1]),
					$this->link('//Book:default', $content[0]),
					$content[2],
					$item->inserted,
					$this->link('//Book:default', $content[1])
				);
			} else
			if ($item->type == FeedItemEntity::TYPE_UPDATED_OPINION) {
				$this->addItem(
					System::translate("%s have just changed opinion on book %s", $item->userNick, $content[1]),
					$this->link('//Book:default', $content[0]),
					$content[2],
					$item->inserted,
					$this->link('//Book:default', $content[1])
				);
			} else
			if ($item->type == FeedItemEntity::TYPE_NEW_POST) {
				$this->addItem(
					System::translate("%s have just contributed new post to discussion %s", $item->userNick, $content[1]),
					$this->link('//Discussion:posts', $content[3],$content[2]),
					$content[4],
					$item->inserted,
					$this->link('//Discussion:posts', $content[3],$content[2])
				);
			}  else
			if ($item->type == FeedItemEntity::TYPE_SHELVED) {
				if ($content[0] == 'owned'){
					$text = System::translate("%s have just added book %s to their library.",$item->userNick,$content[3]);
				}
				if ($content[0] == 'general'){
					$text = System::translate("%s have just added book %s to shelf %s.",$item->userNick,$content[3],$content[1]);
				}
				if ($content[0] == 'wanted'){
					$text = System::translate("%s wants to read %s.",$item->userNick,$content[3]);
				}
				if ($content[0] == 'reading'){
					$text = System::translate("%s have just started reading book %s.",$item->userNick,$content[3]);
				}
				$this->addItem(
					$text,
					null,
					null,
					$item->inserted,
					null
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