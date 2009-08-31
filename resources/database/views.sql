DROP VIEW IF EXISTS `view_book`;
CREATE VIEW `view_book` AS
	SELECT
		`language`.`id_language`			AS `id_language`,
		`language`.`locale`					AS `locale`,
		`book`.`id_book`					AS `id_book`,
		`book_title`.`id_book_title`		AS `id_book_title`,
		`book_title`.`title`				AS `title`,
		`book_title`.`subtitle`				AS `subtitle`,
		`book_title`.`inserted`				AS `inserted`,
		IFNULL(AVG(`opinion`.`rating`),0)	AS `rating`,
		COUNT(`opinion`.`id_opinion`)		AS `number_of_opinions`
	FROM `book`
	INNER JOIN `book_title` USING(`id_book`)
	INNER JOIN `language` USING(`id_language`)
	LEFT JOIN `opinion` USING(`id_book`)
	GROUP BY `book_title`.`id_book_title`;

DROP VIEW IF EXISTS `view_author`;
CREATE VIEW `view_author` AS
	SELECT
		`author`.*,
		IF (`author`.`type` = 'person', CONCAT(`author`.`first_name`, CONCAT(' ', `author`.`last_name`)), `group_name`) AS `full_name`
	FROM `author`

DROP VIEW IF EXISTS `view_book_author`;
CREATE VIEW `view_book_author` AS
	SELECT
		`written_by`.`id_book`			AS `id_book`,
		`view_author`.*
	FROM `written_by`
	INNER JOIN `view_author` USING(`id_author`);

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

DROP VIEW IF EXISTS `view_post`;
CREATE VIEW `view_post` AS
	SELECT
		`post`.`id_post`				AS `id_post`,
		`post`.`reply`					AS `reply`,
		`post`.`subject`				AS `subject`,
		`post`.`content`				AS `content`,
		`post`.`inserted`				AS `inserted`,
		`user`.`id_user`				AS `id_user`,
		`user`.`nick`					AS `user_nick`,
		`discussion`.`id_discussion`	AS `id_discussion`,
		`discussion`.`name`				AS `discussion_name`,
		`discussion`.`id_discussable`	AS `id_discussable`,
		`language`.`id_language`		AS `id_language`,
		`language`.`locale`				AS `locale`
	FROM `post`
	INNER JOIN `language` USING (`id_language`)
	INNER JOIN `user` USING (`id_user`)
	INNER JOIN `discussion` USING (`id_discussion`)

DROP VIEW IF EXISTS `view_topic`;
CREATE VIEW `view_topic` AS
	SELECT
		`topic`.*,
		`user`.`nick`					AS `user_name`
	FROM `topic`
	INNER JOIN `user` USING (`id_user`)

DROP VIEW IF EXISTS `view_book_similarity`;
CREATE VIEW `view_book_similarity` AS
	SELECT
		`from`.`id_book` AS `from_id_book`,
		`to`.`id_book`  AS `to_id_book`,
		2 * COUNT(*) /(SELECT COUNT(`id_tag`) FROM `tagged` WHERE `id_book` = `from`.`id_book` OR `id_book` = `to`.`id_book`) AS `value`
	FROM `tagged` AS `from`
	INNER JOIN `tagged` AS `to` ON `to`.`id_tag` = `from`.`id_tag`
	WHERE `from`.`id_book` != `to`.`id_book`
	GROUP BY `from`.`id_book`, `to`.`id_book`;

DROP VIEW IF EXISTS `view_user_similarity`;
CREATE VIEW `view_user_similarity` AS
	SELECT
		`from`.`id_user` AS `from_id_user`,
		SUM(
			CASE ABS(`from`.`rating` - `to`.`rating`)
				WHEN 0 THEN 16
				WHEN 1 THEN 9
				WHEN 2 THEN 4
				WHEN 3 THEN 2
				WHEN 4 THEN 0
			END
		)
		/
		((SELECT COUNT(*) FROM `opinion` WHERE `opinion`.`id_user` = `from`.`id_user`)*16) AS `value`,
		`to`.`id_user`  AS `to_id_user`
	FROM `opinion` AS `from`
	INNER JOIN opinion AS `to` ON `to`.id_book = `from`.id_book
	WHERE `from`.`id_user` != `to`.`id_user`
	GROUP BY `from`.id_user, `to`.id_user;

DROP VIEW IF EXISTS `view_similar_book`;
CREATE VIEW `view_similar_book` AS
	SELECT
		`view_book`.*,
		`book_similarity`.`id_book_from`,
		`book_similarity`.`value`		AS `similarity`
	FROM `book_similarity`
	INNER JOIN `view_book` ON `book_similarity`.`id_book_to` = `view_book`.`id_book`
	ORDER BY `similarity` DESC

DROP VIEW IF EXISTS `view_similar_user`;
CREATE VIEW `view_similar_user` AS
	SELECT
		`view_user`.*,
		`user_similarity`.`id_user_from`,
		`user_similarity`.`value`	AS `similarity`
	FROM `user_similarity`
	INNER JOIN `view_user` ON `user_similarity`.`id_user_to` = `view_user`.`id_user`
	ORDER BY `similarity` DESC