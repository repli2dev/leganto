<?php
class SubmenuComponent extends BaseComponent
{

    public function addLink($action, $name, $args = NULL) {
	$this->getTemplate()->links[] = new SubmenuLink($action, $name, $args);
    }

    public function addEvent($action, $name, $args = NULL) {
	$this->getTemplate()->events[] = new SubmenuLink($action, $name, $args);
    }

    protected function startUp() {
	parent::startUp();
	$this->getTemplate()->links = array();
	$this->getTemplate()->events = array();
    }

    public function equalArgs($args,$presenter) {
	    $params = $presenter->getParam();
	    unset($params["action"]);
	    // Array and not array args!
	    if(!is_array($args) && count($params) == 1) {
		    $params = end($params);
	    }
	    // Are arrays equal
	    if($params == $args) {
		    return TRUE;
	    } else {
		    return FALSE;
	    }
    }

}


class SubmenuLink {

    private $action;

    private $args;

    private $name;

    public function  __construct($action, $name, $args = array()) {
	$this->action	= $action;
	$this->name	= $name;
	$this->args	= $args;
    }

    public function getAction() {
	return $this->action;
    }

    public function getArgs() {
		return $this->args;
    }

    public function getName() {
	return $this->name;
    }
}