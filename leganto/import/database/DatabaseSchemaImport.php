<?php
class DatabaseSchemaImport implements IImportable
{

    /** @var DibiConnection */
    private $connection;

    private $sources = array();

    public function  __construct(DibiConnection $connection) {
	$this->connection = $connection;
    }

    public function setSource(File $source) {
	if ($tables->isFile()) {
	    throw new IOException("The file " . $tables->getPath() . " does not exist.");
	}
	$this->sources[] = $source;
    }

    public function import() {
	foreach($this->sources AS $source) {
	    $this->connection->loadFile($source->getPath());
	}
    }

}

