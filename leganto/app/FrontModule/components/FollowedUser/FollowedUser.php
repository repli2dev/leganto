<?php

/**
 * Followed which show what followed user has in common with showed book
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Factory;

class FollowedUser extends BaseComponent {

	public function beforeRender() {
		$user = $this->getUser()->getId();
		parent::beforeRender();
		$data = Factory::followed()->getSelector()->findAllByBook($this->getPresenter()->getParam("book"),$user);
		$output = array();
		foreach ($data as $row) {
			$output[$row->type][] = array($row->id_user, $row->nick);
		}
		$data2 = Factory::followed()->getSelector()->findAllOpinionByBook($this->getPresenter()->getParam("book"),$user);
		foreach ($data2 as $row) {
			$output["opinion"][] = array($row->id_user, $row->user_nick);
		}
		$this->getTemplate()->data = $output;
	}

}
