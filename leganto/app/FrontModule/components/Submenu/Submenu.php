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
namespace FrontModule\Components;

class Submenu extends BaseComponent {

	public function addLink($action, $name, $args = NULL, $title = NULL) {
		$this->getTemplate()->links[] = new SubmenuLink($action, $name, $args,NULL,$title);
	}

	public function addEvent($action, $name, $args = NULL, $confirm = NULL, $title = NULL) {
		$this->getTemplate()->events[] = new SubmenuLink($action, $name, $args, $confirm,$title);
	}

	protected function startUp() {
		parent::startUp();
		$this->getTemplate()->links = array();
		$this->getTemplate()->events = array();
	}

	public function equalArgs($args, $presenter) {
		$params = $presenter->getParam();
		unset($params["action"]);
                unset($params["_fid"]);
		// Array and not array args!
		if (!is_array($args) && count($params) == 1) {
			$params = end($params);
		}
		// Are arrays equal
		if ($params == $args) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

class SubmenuLink {

	private $action;
	private $args;
	private $confirm;
	private $name;
	private $title;

	public function __construct($action, $name, $args = array(), $confirm = NULL,$title = NULL) {
		$this->action = $action;
		$this->name = $name;
		$this->args = $args;
		$this->confirm = $confirm;
		$this->title = $title;
	}

	public function getAction() {
		return $this->action;
	}

	public function getArgs() {
		return $this->args;
	}

	public function getConfirm() {
		return $this->confirm;
	}

	public function getName() {
		return $this->name;
	}

	public function getTitle() {
		return $this->title;
	}

}