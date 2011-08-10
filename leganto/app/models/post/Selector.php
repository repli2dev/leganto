<?php

/**
 * Post selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Post;

use Leganto\ORM,
    Leganto\ORM\Workers\ISelector,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\Exceptions,
    InvalidArgumentException,
    Leganto\ORM\Exceptions\NotSupportedException,
    Leganto\DB\Factory;

class Selector extends AWorker implements ISelector {
	const OPINION = 2;

	const TOPIC = 1;

	/**
	 * Find all posts
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->DataSource("SELECT * FROM [view_post]");
	}

	/**
	 * Find all posts by post id and type
	 * @param int $id of post id
	 * @param int $type type (const OPINION and TOPIC)
	 * @return DibiDataSource
	 * @throws InvalidArgumentException if type or id is empty
	 * @throws NotSupportedException if type is not supported
	 */
	public function findAllByIdAndType($id, $type) {
		if (empty($type)) {
			throw new InvalidArgumentException("Empty type.");
		}
		if (empty($id)) {
			throw new InvalidArgumentException("Empty id.");
		}
		if (!in_array($type, array(self::OPINION, self::TOPIC))) {
			throw new NotSupportedException("The entity type is not supported.");
		}
		return $this->connection->DataSource("SELECT * FROM [view_post] WHERE [id_discussable] = %i", $type, " AND [id_discussed] = %i", $id);
	}

	/**
	 * Find one post with certain id
	 * @param int $id
	 * @return \Leganto\DB\Post\Entity
	 * @throws InvalidArgumentException if id is empty
	 */
	public function find($id) {
		if (empty($id)) {
			throw new InvalidArgumentException("Empty id.");
		}
		return Factory::post()->fetchAndCreate($this->connection->DataSource("SELECT * FROM [view_post] WHERE [id_post] = %i", $id));
	}

	/**
	 * Search in posts for certaing keyword(s)
	 * @param string $keyword
	 * @return DibiDataSource
	 * @throw InvalidArgumentException if keyword is empty
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new InvalidArgumentException("Empty keyword.");
		}
		$keyword = "%" . $keyword . "%";
		return $this->connection->dataSource("
			 SELECT * FROM [view_post]
			 WHERE [content] LIKE %s ", $keyword, "OR [user_nick] LIKE %s ", $keyword, "OR [discussion_name] LIKE %s ", $keyword
		);
	}

}
