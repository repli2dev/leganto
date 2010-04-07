<?php
class SubmenuComponent extends BaseComponent
{

    public function setUp(array $links) {
	$this->getTemplate()->links = $links;
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