CREATE VIEW `view_message` AS
	SELECT
		`message`.`id_message` AS `id_message`,
		`message`.`subject` AS `subject`,
		`message`.`content` AS `content`,
		`message`.`inserted` AS `inserted`,
		`message`.`to_destroyed` AS `to_destroyed`,
		`message`.`from_destroyed` AS `from_destroyed`,
		`user_to`.`id_user` AS `to_id_user`,
		`user_to`.`nick` AS `to_nick`,
		`user_from`.`id_user` AS `from_id_user`,
		`user_from`.`nick` AS `from_nick`
	FROM `message`
	INNER JOIN `user` AS `user_to` ON `message`.`id_user_to` = `user_to`.`id_user`
	INNER JOIN `user` AS `user_from` ON `message`.`id_user_from` = `user_from`.`id_user`
	ORDER BY `inserted` DESC