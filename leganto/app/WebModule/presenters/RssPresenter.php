<?php
class RssPresenter extends BasePresenter
{


    public function renderDefault($user = NULL) {
	$source = Leganto::feed()->getSelector()->findAll();
	if (!empty($user)) {
	    $users = Leganto::users()->getSelector()->findAllFollowed($user)->fetchPairs("id_user","id_user");
	    $source->where("id_user IN %l", $users);
	}
	while($item = $source->fetch()) {
	}
    }

    protected function startUp() {
	$this->getTemplate()->items = array();
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

    public function  __construct($title, $link, $description, $date, $guid) {
	$this->title	    = $title;
	$this->link	    = $link;
	$this->description  = $description;
	$this->date	    = $date;
	$this->guid	    = $guid;
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