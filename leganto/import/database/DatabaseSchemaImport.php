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
	if (!$source->isFile()) {
	    throw new IOException("The file " . $source->getPath() . " does not exist.");
	}
	$this->sources[] = $source;
    }

    public function import() {
	foreach($this->sources AS $source) {
	    $this->connection->loadFile($source->getPath());
	}
    }

}

