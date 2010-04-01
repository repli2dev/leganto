<?php
abstract class DatabaseTableImport extends EskymoObject implements IImportable
{

    private $destination;

    private $source;

    public function  __construct(DibiConnection $source, DibiConnection $destination) {
	$this->destination  = $destination;
	$this->source	    = $source;
    }


    public function import() {
	try {
	    $this->getDestination()->begin();
	    $this->doImport();
	    $this->getDestination()->commit();
	}
	catch(DibiDriverException $e) {
	    try {
		$this->getDestination()->rollback();
	    }
	    catch(DibiException $e) {}
	    throw new DibiDriverException($e->getMessage(), $e->getCode(), $e->getSql());
	}
    }

    protected abstract function doImport();

    /** @return DibiConnection */
    protected final function getDestination() {
	return $this->destination;
    }

    /** @return DibiConnection */
    protected final function getSource() {
	return $this->source;
    }

    private function getSavepoint() {
	return String::lower($this->getClass());
    }

}
