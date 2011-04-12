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
class FollowedUserComponent extends BaseComponent {

	public function  beforeRender() {
		parent::beforeRender();
		$data = Leganto::followed()->getSelector()->findAllByBook($this->getPresenter()->getParam("book"));
		$output = array();
		foreach($data as $row) {
			$output[$row->type][] = array($row->id_user,$row->nick);
		}
		$data2 = Leganto::followed()->getSelector()->findAllOpinionByBook($this->getPresenter()->getParam("book"));
		foreach($data2 as $row) {
			$output["opinion"][] = array($row->id_user,$row->user_nick);
		}
		$this->getTemplate()->data = $output;
	}
}
