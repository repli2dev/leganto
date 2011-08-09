<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class PostSelector implements ISelector {
	const OPINION = 2;

	const TOPIC = 1;

	/**
	 * Find all posts
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::DataSource("SELECT * FROM [view_post]");
	}

	/**
	 * Find all posts by post id and type
	 * @param int $id of post id
	 * @param int $type type (const OPINION and TOPIC)
	 * @return DibiDataSource
	 */
	public function findAllByIdAndType($id, $type) {
		if (empty($type)) {
			throw new NullPointerException("type");
		}
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		if (!in_array($type, array(self::OPINION, self::TOPIC))) {
			throw new NotSupportedException("The entity type is not supported");
		}
		return dibi::DataSource("SELECT * FROM [view_post] WHERE [id_discussable] = %i", $type, " AND [id_discussed] = %i", $id);
	}

	/**
	 * Find one post with certain id
	 * @param int $id
	 * @return PostEntity
	 */
	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::posts()->fetchAndCreate(dibi::DataSource("SELECT * FROM [view_post] WHERE [id_post] = %i", $id));
	}

	/**
	 * Search in posts for certaing keyword(s)
	 * @param string $keyword
	 * @return DibiDataSource
	 */
	public function search($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keyword = "%" . $keyword . "%";
		return dibi::dataSource("
			 SELECT * FROM [view_post]
			 WHERE [content] LIKE %s ", $keyword,
			"OR [user_nick] LIKE %s ", $keyword,
			"OR [discussion_name] LIKE %s ", $keyword
		);
	}

}
