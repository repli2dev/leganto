<?php

/**
 * Implements basic method of workers (inserter, deleter, selector, updater)
 * @author Jan Drabek
 */
namespace Leganto\ORM\Workers;
use DibiConnection;

abstract class AWorker {
	
	/** @var DibiConnection */
	protected $connection;
	
	public function __construct($connection) {
		if(empty($connection) || ! $connection instanceof DibiConnection) {
			throw new InvalidArgumentException("Empty connection.");
		}
		$this->connection = $connection;
	}
}
?>
