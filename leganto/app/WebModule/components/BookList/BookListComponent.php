<?php
class BookListComponent extends BaseComponent
{

    /** @var int */
    private $limit = 10;

    /** @persistent */
    public $orderColumn;

    /** @persistent */
    public $orderDirection;

    /** @persistent */
    public $page;

    /** @var bool */
    private $setupHappened = false;

    public function getLimit() {
	return $this->limit;
    }

    public function handleOrder($column) {
	$this->setOrderColumn($column);
    }

    public function handlePage($page) {
	$this->setPage($page);
    }

    /**
     * It has to be called before setup
     * @param int $limit
     */
    public function setLimit($limit) {
	if ($this->setupHappened) {
	    throw new InvalidStateException("The method [setLimit] has to be called before setup.");
	}
	$this->limit = $limit;
    }

    public function setUp(DibiDataSource $source) {
	if ($this->setupHappened) {
	    throw new InvalidStateException("The method [setUp] can not be called more than once.");
	}
	$this->setupHappened = true;
	$this->loadTemplate($source);
    }

    // PRIVATE METHODS

    private function getOrderColumn() {
	if (empty($this->orderColumn)) {
	    return"number_of_opinions";
	}
	else {
	    return $this->orderColumn;
	}
    }

    private function getOrderDirection() {
	if (empty ($this->orderDirection)) {
	    return 'DESC';
	}
	else {
	    return $this->orderDirection;
	}
    }

    private function getPage() {
	if (empty($this->page)) {
	    return 0;
	}
	else {
	    return $this->page;
	}
    }

    private function loadTemplate(DibiDataSource $source) {
	$books	    = $source
	    ->orderBy($this->getOrderColumn(), $this->getOrderDirection())
	    ->applyLimit($this->getLimit(), $this->getPage() * $this->getLimit())
	    ->fetchAssoc("id_book_title");
	$authors    = $source
	    ->where("[id_book_title] IN %l", array_keys($books))
	    ->fetchAssoc("id_book_title,id_author");
	// Books and covers
	$this->getTemplate()->books = array();
	$this->getTemplate()->covers = array();
	$storage = new EditionImageStorage();
	foreach($books as $book) {
	    // Book
	    $entity = Leganto::books()->createEmpty()->loadDataFromArray($book->getArrayCopy());
	    $this->getTemplate()->books[] = $entity;
	    // Cover
	    $image = $storage->getRandomFileByBook($entity);
	    $this->getTemplate()->covers[$entity->getId()] = empty($image) ? NULL : $image->getAbsolutePath();
	}
	// Authors
	$this->getTemplate()->authors = array();
	foreach($authors as $bookTitleId => $authorGroup) {
	    $this->getTemplate()->authors[$bookTitleId] = array();
	    foreach ($authorGroup AS $author) {
		$entity = Leganto::authors()->createEmpty()->loadDataFromArray($author->getArrayCopy(), "Load");
		$this->getTemplate()->authors[$bookTitleId][] = $entity;
	    }
	}
    }

    private function setOrderColumn($column) {
	switch($column) {
	    case "rating":
	    case "title":
	    case "number_of_opinions":
		$this->orderColumn = $column;
		break;
	    default:
		throw new InvalidArgumentException("The column name [$column] is illegal.");
	}
    }

    private function setOrderDirection($asc) {
	if ($asc) {
	    $this->orderDirection = 'ASC';
	}
	else {
	    $this->orderDirection = 'DESC';
	}
    }

    private function setPage($page) {
	if ($page <= 0) {
	    $this->page = 0;
	}
	else {
	    $this->page = $page;
	}
    }

}
