<?php
class SystemImport implements IImportable
{

    /** @var DibiConnection */
    private $connection;

    private $languages = array();

    public function  __construct(DibiConnection $connection) {
	$this->connection = $connection;
    }

    public function import() {
	try {
	    $this->connection->begin("system");
	    $this->insertLanguages();
	    $this->insertDomain();
	    $this->connection->commit("system");
	}
	catch(DibiDriverException $e) {
	    $this->connection->rollback("system");
	    throw $e;
	}
    }

    private function insertDomain() {
	$domains = array(
	    "preader"		=> "czech",
	    "devel.ctenari.cz"	=> "czech",
	    "ctenari.cz"	=> "czech"
	);
	foreach($domains AS $domain => $language) {
	    $this->connection->insert("domain", array(
		"id_language"   => $this->languages[$language],
		"uri"		=> $domain
	    ))->execute();
	}
    }

    private function insertLanguages() {
	$this->connection->insert("language", array(
	    "name"	=> "czech",
	    "locale"	=> "cs"
	    ))->execute();
	$this->languages["czech"] = $this->connection->insertId();
    }

}

