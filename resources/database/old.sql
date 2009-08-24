 SELECT *
FROM `reader_user`
GROUP BY `email`
HAVING COUNT( * ) >1
LIMIT 0 , 30

SELECT * FROM `reader_user` WHERE `email` IN (SELECT `email` FROM `reader_user` GROUP BY `email` HAVING COUNT( * ) >1)

SELECT * FROM `reader_user` WHERE `email` IN ('bakesl@seznam.cz', 'Beecky@centrum.cz', 'cervik.niki@seznam.cz','fanula1@seznam.cz', 'hanzalova.b@seznam.cz', 'houfkovakaja@seznam.cz', 'info@martinjanda.com', 'tomas.fojtik@gmail.com', 'v.kubalova@volny.cz')

CREATE VIEW `tmp_view_user` AS
	SELECT
		`u`.`id`	AS `id`,
		`u`.`email` AS `email`,
		`u`.`name`	AS `name`,
		`u`.`login`	AS `logged`,
		COUNT(`o`.`id`) AS `opinions`,
		(SELECT COUNT(*) FROM `reader_discussion` AS `d` WHERE `d`.`user` = `u`.`id`) AS `discussions`,
		(SELECT COUNT(*) FROM `reader_user` AS `help` WHERE `help`.`email` = `u`.`email` GROUP BY `email`) AS emails
	FROM `reader_user` AS `u`
	LEFT JOIN `reader_opinion` AS `o` ON `u`.`id` = `o`.`user`
	GROUP BY `u`.`id`
	ORDER BY `emails` DESC, `email`, `logged` DESC

DELETE FROM `reader_user` WHERE `email` IN ('enzymnelono@mail.ru')

DELETE FROM `reader_opinion` WHERE `user` = <id>

DELETE FROM `reader_opinion` WHERE `user` IN (284, 282, 81, 587, 442, 772, 337, 615, 337, 38, 149);

DELETE FROM `reader_discussion` WHERE `user` IN (284, 282, 81, 587, 442, 772, 337, 615, 337, 38, 149);

DELETE FROM `reader_user` WHERE `id` IN (284, 282, 81, 587, 442, 772, 337, 615, 337, 38, 149);