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
	    $this->insertLanguages();
	    $this->insertDomain();
	}
	catch(DibiDriverException $e) {
	    //$this->connection->rollback("system");
	    throw $e;
	}
    }

    private function insertDomain() {
	$domains = array(
	    "preader"               => "en_US",
            "leganto"               => "en_US",
	    "leganto.yavanna.cz"    => "en_US",
	    "ctenari.cz"            => "cs_CZ"
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
	    "name"	=> "Česky",
	    "locale"	=> "cs_CZ"
	    ))->execute();
        $this->languages["cs_CZ"] = $this->connection->insertId();
	$this->connection->insert("language", array(
	    "name"	=> "English",
	    "locale"	=> "en_US"
	    ))->execute();
        $this->languages["en_US"] = $this->connection->insertId();
    }

}

