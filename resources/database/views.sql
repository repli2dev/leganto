DROP VIEW IF EXISTS `view_book`;
CREATE VIEW `view_book` AS
	SELECT
		`language`.`id_language`			AS `id_language`,
		`language`.`locale`					AS `locale`,
		`book`.`id_book`					AS `id_book`,
		`book_title`.`id_book_title`		AS `id_book_title`,
		`book_title`.`title`				AS `title`,
		`book_title`.`subtitle`				AS `subtitle`,
		IFNULL(AVG(`opinion`.`rating`),0)	AS `rating`,
		COUNT(`opinion`.`id_opinion`)		AS `number_of_opinions`
	FROM `book`
	INNER JOIN `book_title` USING(`id_book`)
	INNER JOIN `language` USING(`id_language`)
	LEFT JOIN `opinion` USING(`id_book`)
	GROUP BY `book_title`.`id_book_title`;

DROP VIEW IF EXISTS `view_author`;
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

DROP VIEW IF EXISTS `view_book_tag`;
CREATE VIEW `view_book_tag` AS
	SELECT
		`tagged`.`id_book`				AS `id_book`,
		`tag`.`id_language`				AS `id_language`,
		`tag`.`id_tag`					AS `id_tag`,
		`tag`.`name`					AS `name`
	FROM `tagged`
	INNER JOIN `tag` USING(`id_tag`);

DROP VIEW IF EXISTS `view_shelf_book`;
CREATE VIEW `view_shelf_book` AS
	SELECT
		`shelf`.`id_shelf`				AS `id_shelf`,
		`shelf`.`id_user`				AS `id_user`,
		`view_book`.*
	FROM `in_shelf`
	INNER JOIN `shelf` USING (`id_shelf`)
	INNER JOIN `view_book` USING (`id_book`);

DROP VIEW IF EXISTS `view_author_book`;
CREATE VIEW `view_author_book` AS
	SELECT
		`written_by`.`id_author`		AS `id_author`,
		`view_book`.*
	FROM `written_by`
	INNER JOIN `view_book` USING(`id_book`);

DROP VIEW IF EXISTS `view_shelf`;
CREATE VIEW `view_shelf` AS
	SELECT
		`shelf`.`id_shelf`				AS `id_shelf`,
		`shelf`.`name`					AS `name`,
		`shelf`.`type`					AS `type`,
		`shelf`.`id_user`				AS `id_user`,
		`user`.`nick`					AS `user_nick`,
		COUNT(`in_shelf`.`id_in_shelf`)	AS `number_of_books`
	FROM `shelf`
	LEFT JOIN `in_shelf` USING (`id_shelf`)
	LEFT JOIN `user` USING(`id_user`)
	GROUP BY `shelf`.`id_shelf`

DROP VIEW IF EXISTS `view_opinion`;
CREATE VIEW `view_opinion` AS
	SELECT
		`opinion`.`id_opinion`			AS `id_opinion`,
		`opinion`.`content`				AS `content`,
		`opinion`.`rating`				AS `rating`,
		`opinion`.`inserted`			AS `inserted`,
		`opinion`.`id_book`				AS `id_book`,
		`user`.`id_user`				AS `id_user`,
		`user`.`nick`					AS `user_nick`,
		`language`.`id_language`		AS `id_language`,
		`language`.`locale`				AS `locale`
	FROM `opinion`
	INNER JOIN `language` USING(`id_language`)
	INNER JOIN `user` USING(`id_user`)