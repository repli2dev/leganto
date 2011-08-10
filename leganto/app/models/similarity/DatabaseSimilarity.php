<?php

/**
 * Tool for managing similarities (books, users)
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Similarity;

use InvalidArgumentException,
    DibiConnection;

class DatabaseSimilarity implements ISimilarity {

	private $entity;
	private $item;
	private $source;
	private $rating;
	private $maximum;

	/** @var DibiConnection */
	private $connection;

	/**
	 * Inject DIBI database connection
	 * 
	 * @param DibiConnection $connection Inject DIBI database connection
	 */
	public function setConnection(DibiConnection $connection) {
		$this->connection = $connection;
	}

	public function __construct($source, $entity, $item, $rating = NULL, $maximum = 1) {
		if (empty($source)) {
			throw new InvalidArgumentException("Empty source.");
		}
		if (empty($entity)) {
			throw new InvalidArgumentException("Empty entity.");
		}
		if (empty($item)) {
			throw new InvalidArgumentException("Empty item.");
		}
		if (empty($maximum)) {
			throw new InvalidArgumentException("Empty item.");
		}
		if ($maximum < 1) {
			throw new InvalidArgumentException("Empty maximum.");
		}
		$this->source = $source;
		$this->entity = $entity;
		$this->item = $item;
		$this->rating = $rating;
		$this->maximum = $maximum;
	}

	public function checkout() {
		$source = $this->source;
		$entity = $this->entity;
		$item = $this->item;
		$this->connection->begin();
		// Drop old tables if exist.
		$this->connection->query("DROP TABLE IF EXISTS [" . $entity . "_similarity]");
		$this->connection->query("DROP TABLE IF EXISTS [" . $entity . "_similarity_computed]");
		// Save result of query which selects similarity
		$this->connection->query("
			CREATE TABLE [" . $entity . "_similarity]
			(INDEX([id_" . $entity . "_to]), INDEX([id_" . $entity . "_from]))
			" . $this->getComputingQuery()
		);
		$this->connection->query("
			CREATE TABLE [" . $entity . "_similarity_computed]
			(INDEX([id_" . $entity . "]))
			SELECT
				[id_" . $entity . "],
				NOW()				AS [updated]
			FROM [" . $source . "]
			GROUP BY [id_" . $entity . "]
		");
		$this->connection->commit();
	}

	public function update() {
		throw new NotImplementedException();
		$source = $this->source;
		$toUpdate = $this->getIdsToUpdate()->fetchPairs("id", "id");
		$entity = $this->entity;
		$this->connection->begin();
		$this->connection->query("
			DELETE FROM [" . $entity . "_similarity]
			WHERE [id_" . $entity . "_from] IN %l", $toUpdate, "
			OR [id_" . $entity . "_to] IN %l", $toUpdate
		);
		$this->connection->query("
			DELETE FROM [" . $entity . "_similarity_computed]
			WHERE [id_" . $entity . "] IN %l", $toUpdate
		);
		$query = $this->getComputingQuery($source, $entity, $this->item)
			->where("[id_" . $entity . "_to] IN %l", $toUpdate, " OR [id_" . $entity . "_from] IN %l", $toUpdate);
		$this->connection->query("
			INSERT INTO [" . $entity . "_similarity]
			
			" . $query . "
		");
		$this->connection->query("
			INSERT INTO [" . $entity . "_similarity_computed]
			SELECT
				[id_" . $entity . "],
				NOW()				AS [updated]
			FROM [" . $source . "]
			WHERE [id_" . $entity . "] IN %l", $toUpdate, "
			GROUP BY [id_" . $entity . "]
		");
		$this->connection->commit();
	}

	public function updateOne() {
		throw new NotImplementedException();
	}

	public function getComputingQuery() {
		$maximum = $this->maximum ^ 2;
		if (empty($this->rating)) {
			$numerator = "COUNT(*)";
		} else {
			$numerator = "SUM(" . (($this->maximum - 1) ^ 2) . "- ([to].[$this->rating] - [from].[$this->rating])*([to].[$this->rating] - [from].[$this->rating]))";
		}
		return $this->connection->dataSource("
			SELECT
				[from].[id_" . $this->entity . "]		AS [id_" . $this->entity . "_from],
				[to].[id_" . $this->entity . "]		AS [id_" . $this->entity . "_to],
				
				($numerator) / (SELECT $maximum*COUNT(*) FROM [$this->source] WHERE [id_" . $this->entity . "] = [from].[id_" . $this->entity . "])
								AS [value]
			FROM [" . $this->source . "] AS [from]
			INNER JOIN [" . $this->source . "] AS [to]
				ON [to].[id_" . $this->item . "] = [from].[id_" . $this->item . "]
			WHERE [from].[id_" . $this->entity . "] != [to].[id_" . $this->entity . "]
			GROUP BY [from].[id_" . $this->entity . "], [to].[id_" . $this->entity . "]
		");
	}

	private function getIdsToUpdate() {
		return $this->connection->dataSource("
			SELECT
				[source].[id_" . $this->entity . "]	AS [id]
			FROM [" . $this->source . "] AS [source]
			LEFT JOIN [" . $this->entity . "_similarity_computed] AS [computed] USING(id_" . $this->entity . ")
			WHERE
				[computed].[updated] < [source].[updated]
				OR
				[computed].[updated] IS NULL
			ORDER BY [source].[updated]
			LIMIT 1
		");
	}

}

