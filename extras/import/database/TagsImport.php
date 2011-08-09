<?php
class TagsImport extends DatabaseTableImport {

    protected function doImport() {
	$this->getDestination()->query("TRUNCATE TABLE [tagged]");
	$this->getDestination()->query("TRUNCATE TABLE [tag]");
	$this->importTags();
	$this->importTagged();
    }

    private function importTags() {
	$tags	    = $this->getSource()->query("SELECT * FROM [reader_tag]")->fetchAll();
	$language   = $this->getDestination()->query("SELECT * FROM [language] WHERE [locale] = 'cs_CZ'")->fetch();
	foreach($tags AS $tag) {
	    $this->getDestination()->insert("tag", array(
		"id_language"	=> $language["id_language"],
		"name"		=> trim($tag["name"]),
		"id_tag"	=> $tag["id"]
		))->execute();
	}
	echo $this->getDestination()->query("SELECT * FROM [tag]")->count() . " TAGS IMPORTED\n";
    }

    private function importTagged() {
	$books	= $this->getDestination()->query("SELECT * FROM [book]")->fetchPairs("id_book", "id_book");
	$tags	= $this->getDestination()->query("SELECT * FROM [tag]")->fetchPairs("id_tag", "id_tag");
	$tagged = dibi::query("SELECT * FROM [reader_tagReference] WHERE [type] = 'book' AND [tag] IN %l", $tags, " AND [target] IN %l", $books)->fetchAll();
	foreach($tagged AS $row) {
	    try {
		$this->getDestination()->insert("tagged", array(
		    "id_tag"	=> $row["tag"],
		    "id_book"	=> $row["target"]
		    ))->execute();
	    }
	    catch(DibiDriverException $e) {}
	}
	echo $this->getDestination()->query("SELECT * FROM [tagged]")->count() . " TAG RELATIONS IMPORTED\n";
    }
}

