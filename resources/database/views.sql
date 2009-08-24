CREATE VIEW `view_book` AS
	SELECT
		`language`.`id_language`			AS `id_language`,
		`language`.`locale`					AS `locale`,
		`book`.`id_book`					AS `id_book`,
		`book_title`.`id_book_title`		AS `id_book_title`,
		`book_title`.`title`				AS `title`,
		`book_title`.`subtitle`				AS `subtitle`,
		IFNULL(AVG(`opinion`.`rating`),0)	AS `rating`
	FROM `book`
	INNER JOIN `book_title` USING(`id_book`)
	INNER JOIN `language` USING(`id_language`)
	LEFT JOIN `opinion` USING(`id_book`)
	GROUP BY `book_title`.`id_book_title`;

CREATE VIEW `view_book_author` AS
	SELECT
		`written_by`.`id_book`			AS `id_book`,
		`author`.`id_author`			AS `id_author`,
		`author`.`type`					AS `type`,
		`author`.`group_name`			AS `group_name`,
		`author`.`first_name`			AS `first_name`,
		`author`.`last_name`			AS `last_name`
	FROM `written_by`
	INNER JOIN `author` USING(`id_author`);

CREATE VIEW `view_book_tag` AS
	SELECT
		`tagged`.`id_book`				AS `id_book`,
		`tag`.`id_language`				AS `id_language`,
		`tag`.`id_tag`					AS `id_tag`,
		`tag`.`name`					AS `name`
	FROM `tagged`
	INNER JOIN `tag` USING(`id_tag`);

CREATE VIEW `view_shelf_book` AS
	SELECT
		`shelf`.`id_shelf`				AS `id_shelf`,
		`shelf`.`id_user`				AS `id_user`,
		`view_book`.*
	FROM `in_shelf`
	INNER JOIN `shelf` USING (`id_shelf`)
	INNER JOIN `view_book` USING (`id_book`);

CREATE VIEW `view_author_book` AS
	SELECT
		`written_by`.`id_author`		AS `id_author`,
		`view_book`.*
	FROM `written_by`
	INNER JOIN `view_book` USING(`id_book`);

CREATE VIEW `view_shelf` AS
	SELECT
		`shelf`.`id_shelf`				AS `id_shelf`,
		`shelf`.`name`					AS `name`,
		`shelf`.`type`					AS `type`,
		`shelf`.`id_user`				AS `id_user`,
		COUNT(`in_shelf`.`id_in_shelf`)	AS `number_of_books`
	FROM `shelf`
	LEFT JOIN `in_shelf` USING (`id_shelf`)
	GROUP BY `shelf`.`id_shelf`