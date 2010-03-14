<?php
class BooksImport extends DatabaseTableImport {

    protected function doImport() {
	$this->getDestination()->query("TRUNCATE TABLE [edition]");
	$this->getDestination()->query("TRUNCATE TABLE [opinion]");
	$this->getDestination()->query("TRUNCATE TABLE [written_by]");
	$this->getDestination()->query("TRUNCATE TABLE [author]");
	$this->getDestination()->query("TRUNCATE TABLE [book_title]");
	$this->getDestination()->query("TRUNCATE TABLE [book]");
	$this->importAuthors();
	$this->importBooks();
    }

    private function importAuthors() {
	$authors = $this->getSource()->query("SELECT * FROM [reader_writer] ORDER BY [id]")->fetchAll();
	foreach($authors AS $author) {
	    $parts = explode(" ", $author["name"]);
	    $lastname = trim($parts[0]);
	    $firstname = "";
	    for($i=1; $i<count($parts); $i++) {
		$firstname .= $parts[$i] . " ";
	    }
	    $firstname = trim($firstname);
	    $this->getDestination()->insert("author", array(
		'type'		=> 'person',
		'first_name'	=> $firstname,
		'last_name'	=> $lastname,
		'inserted'	=> new DibiVariable("now()", "sql"),
		'id_author'	=> $author["id"]
		))->execute();
	}
	echo sizeof($authors) . " AUTHORS IMPORTED\n";
    }

    private function importBooks() {
	$books	    = $this->getSource()->query("SELECT * FROM [reader_book] ORDER BY [id]")->fetchAll();
	$language   = $this->getDestination()->query("SELECT * FROM [language] WHERE [name] = 'czech'")->fetch();
	foreach($books AS $book) {
	    // BOOK
	    $this->getDestination()->insert("book", array(
		'inserted'	=> new DibiVariable("now()", "sql"),
		'id_book'	=> $book["id"]
		))->execute();
	    // TITLE
	    $title = trim($book["title"]);
	    $this->getDestination()->insert("book_title", array(
		"id_book"	=> $book["id"],
		"id_language"	=> $language["id_language"],
		"title"		=> $title,
		'inserted'	=> new DibiVariable("now()", "sql"),
		))->execute();
	    // WRITTEN BY
	    $this->getDestination()->insert("written_by", array(
		"id_author"	=> $book["writer"],
		"id_book"	=> $book["id"]
		))->execute();
	}
	echo sizeof($books) . " BOOKS IMPORTED\n";
    }

}