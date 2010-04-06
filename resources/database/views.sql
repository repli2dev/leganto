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
	LEFT JOIN `opinion` USING(`id_book_title`)
	GROUP BY `book_title`.`id_book_title`;

DROP VIEW IF EXISTS `view_author`;
CREATE VIEW `view_author` AS
	SELECT
		`author`.*,
		IF (`author`.`type` = 'person', CONCAT(`author`.`first_name`, CONCAT(' ', `author`.`last_name`)), `group_name`) AS `full_name`
	FROM `author`;

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
	INNER JOIN `view_book` USING (`id_book`)
	ORDER BY `in_shelf`.`order`;

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
	GROUP BY `shelf`.`id_shelf`;

DROP VIEW IF EXISTS `view_opinion`;
CREATE VIEW `view_opinion` AS
	SELECT
		`opinion`.`id_opinion`			AS `id_opinion`,
		`opinion`.`content`			AS `content`,
		`opinion`.`rating`			AS `rating`,
		`opinion`.`inserted`			AS `inserted`,
		`opinion`.`id_book_title`		AS `id_book_title`,
		`user`.`id_user`			AS `id_user`,
		`user`.`nick`				AS `user_nick`,
		`language`.`id_language`		AS `id_language`,
		`language`.`locale`			AS `locale`
	FROM `opinion`
	INNER JOIN `language` USING(`id_language`)
	INNER JOIN `user` USING(`id_user`)
	ORDER BY `opinion`.`inserted` DESC;

DROP VIEW IF EXISTS `view_similar_opinion`;
CREATE VIEW `view_similar_opinion` AS
	SELECT
		`user_similarity`.`value`			AS `similarity`,
		`user_similarity`.`id_user_from`	AS `id_user_from`,
		`view_opinion`.*
	FROM `view_opinion`
	LEFT JOIN `user_similarity` ON `view_opinion`.`id_user` = `user_similarity`.`id_user_to`
	ORDER BY `user_similarity`.`value` DESC, `inserted` DESC;

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
		`discussion`.`id_discussed`	AS `id_discussed`,
		`language`.`id_language`		AS `id_language`,
		`language`.`locale`				AS `locale`
	FROM `post`
	INNER JOIN `language` USING (`id_language`)
	INNER JOIN `user` USING (`id_user`)
	INNER JOIN `discussion` USING (`id_discussion`);

DROP VIEW IF EXISTS `view_topic`;
CREATE VIEW `view_topic` AS
	SELECT
		`topic`.*,
		`user`.`nick`					AS `user_name`
	FROM `topic`
	INNER JOIN `user` USING (`id_user`);

DROP VIEW IF EXISTS `view_discussion`;
CREATE VIEW `view_discussion` AS
	SELECT
		`discussion`.*,
		COUNT(`post`.`id_post`)			AS `number_of_posts`,
		MAX(`post`.`inserted`)			AS `last_post_inserted`
	FROM `discussion`
	INNER JOIN `post` USING(`id_discussion`)
	GROUP BY `id_discussion`
	ORDER BY `last_post_inserted` DESC;

DROP VIEW IF EXISTS `view_similar_book`;
CREATE VIEW `view_similar_book` AS
	SELECT
		`view_book`.*,
		`book_similarity`.`id_book_from`,
		`book_similarity`.`value`		AS `similarity`
	FROM `book_similarity`
	INNER JOIN `view_book` ON `book_similarity`.`id_book_to` = `view_book`.`id_book`
	ORDER BY `similarity` DESC;

DROP VIEW IF EXISTS `view_similar_user`;
CREATE VIEW `view_similar_user` AS
	SELECT
		`user`.*,
		`user_similarity`.`id_user_from`,
		`user_similarity`.`value`	AS `similarity`
	FROM `user_similarity`
	INNER JOIN `user` ON `user_similarity`.`id_user_to` = `user`.`id_user`
	ORDER BY `similarity` DESC;
	
DROP VIEW IF EXISTS `view_book_search`;
CREATE VIEW `view_book_search` AS
	SELECT
		`view_book`.*,
		`tag`.`name`,
		`author`.`id_author`,
		`author`.`type`,
		`author`.`first_name`,
		`author`.`last_name`,
		`author`.`group_name`
	FROM `view_book`
	LEFT JOIN `tagged` USING (`id_book`)
	LEFT JOIN `tag` USING (`id_tag`)
	INNER JOIN `written_by` USING (`id_book`)
	INNER JOIN `author` USING (`id_author`);

DROP VIEW IF EXISTS `view_domain`;
CREATE VIEW `view_domain` AS
	SELECT
		`domain`.*,
		`language`.`locale`
	FROM `domain`
	INNER JOIN `language` USING(`id_language`);

DROP VIEW IF EXISTS `view_feed`;
CREATE VIEW `view_feed` AS 
    (
	SELECT
	    `view_opinion`.`id_user`,
	    `view_opinion`.`user_nick`,
	    'opinion'				AS `type`,
	    `view_opinion`.`id_opinion`		AS `id_item`,
	    ''					AS `item_name`,
	    `view_opinion`.`id_book_title`	AS `id_category`,
	    `book_title`.`title`		AS `category_name`,
	    `view_opinion`.`content`,
	    `view_opinion`.`inserted`
	FROM `view_opinion`
	INNER JOIN `book_title` USING(`id_book_title`)
    )
    UNION
    (
	SELECT
	    `view_post`.`id_user`,
	    `view_post`.`user_nick`,
	    'post'			AS `type`,
	    `view_post`.`id_post`	AS `id_item`,
	    `view_post`.`subject`	AS `item_name`,
	    `view_post`.`id_discussion` AS `id_category`,
	    `view_post`.`discussion_name` AS `category_name`,
	    `view_post`.`content`,
	    `view_post`.`inserted`
	FROM `view_post`
    ) ORDER BY `inserted` DESC;
