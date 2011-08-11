<?php

/**
 * RSS subscribtion of user feed
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use Leganto\DB\Factory,
    Leganto\DB\Feed\Entity;

class RssPresenter extends BasePresenter {
	const LIMIT = 10;

	public function renderDefault($user = NULL) {
		$source = Factory::feed()->getSelector()->findAll();
		if (!empty($user)) {
			$userEntity = Factory::user()->getSelector()->find($user);
			$users = Factory::user()->getSelector()->findAllFollowed($userEntity)->fetchPairs("id_user", "id_user");
			// Only apply if there are any followed users
			if (count($users) > 0) {
				$source->where("id_user IN %l", $users);
			}
		}
		$source->applyLimit(self::LIMIT);
		foreach (Factory::feed()->fetchAndCreateAll($source) AS $item) {
			$content = explode("#$#", $item->content);
			if ($item->type == Entity::TYPE_NEW_OPINION) {
				$this->addItem(
					$this->translate("%s have just contributed new opinion on book %s", $item->userNick, $content[1]), $this->link('//Book:default', $content[0]), $content[2], $item->inserted, $this->link('//Book:default', $content[1])
				);
			} else
			if ($item->type == Entity::TYPE_UPDATED_OPINION) {
				$this->addItem(
					$this->translate("%s have just changed opinion on book %s", $item->userNick, $content[1]), $this->link('//Book:default', $content[0]), $content[2], $item->inserted, $this->link('//Book:default', $content[1])
				);
			} else
			if ($item->type == Entity::TYPE_NEW_POST) {
				$this->addItem(
					$this->translate("%s have just contributed new post to discussion %s", $item->userNick, $content[1]), $this->link('//Discussion:posts', $content[3], $content[2]), $content[4], $item->inserted, $this->link('//Discussion:posts', $content[3], $content[2])
				);
			} else
			if ($item->type == Entity::TYPE_SHELVED) {
				if ($content[0] == 'owned') {
					$text = $this->translate("%s have just added book %s to their library.", $item->userNick, $content[3]);
				}
				if ($content[0] == 'general') {
					$text = $this->translate("%s have just added book %s to shelf %s.", $item->userNick, $content[3], $content[1]);
				}
				if ($content[0] == 'wanted') {
					$text = $this->translate("%s wants to read %s.", $item->userNick, $content[3]);
				}
				if ($content[0] == 'reading') {
					$text = $this->translate("%s have just started reading book %s.", $item->userNick, $content[3]);
				}
				$this->addItem(
					$text, null, null, $item->inserted, null
				);
			}
		}
	}

	protected function startUp() {
		parent::startUp();
		$this->getTemplate()->items = array();
		$this->getTemplate()->domain = $this->getService("environment")->domain();
	}

	private function addItem($title, $link, $description, $date, $guid) {
		$this->getTemplate()->items[] = new RssItem($title, $link, $description, $date, $guid);
	}

}

/**
 * One item in RSS
 * @author Jan Drabek
 * @author Jan Papousek
 */
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