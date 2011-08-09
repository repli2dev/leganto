OK: SELECT * 
			FROM (SELECT * FROM `user` WHERE `id_user` = 445) t;
-- rows: 1
-- takes: 16.163 ms
-- driver: mysql/0
-- 2010-07-17 11:45:30

OK: SELECT COUNT(*) FROM (SELECT * FROM `view_book_search`  WHERE 
			(`title` LIKE '%román%' OR
			`subtitle` LIKE '%román%' OR
			`name` LIKE '%román%' OR
			`first_name` LIKE '%román%' OR
			`last_name` LIKE '%román%' OR
			`group_name` LIKE '%román%') GROUP BY `id_book`) t;
-- rows: 1
-- takes: 17.272 ms
-- driver: mysql/0
-- 2010-07-17 11:45:30

OK: SELECT * 
			FROM (SELECT * FROM `view_book_search`  WHERE 
			(`title` LIKE '%román%' OR
			`subtitle` LIKE '%román%' OR
			`name` LIKE '%román%' OR
			`first_name` LIKE '%román%' OR
			`last_name` LIKE '%román%' OR
			`group_name` LIKE '%román%') GROUP BY `id_book`) t 
			 
			 
			  LIMIT 12;
-- rows: 12
-- takes: 17.108 ms
-- driver: mysql/0
-- 2010-07-17 11:45:31

OK: SELECT * 
			FROM (SELECT * FROM `view_book_author`) t 
			 WHERE (`id_book` IN (1, 4, 5, 6, 7, 8, 10, 14, 15, 17, 18, 22));
-- rows: 12
-- takes: 31.920 ms
-- driver: mysql/0
-- 2010-07-17 11:45:31

OK: SELECT * 
			FROM (SELECT * FROM `user` WHERE `id_user` = 445) t;
-- rows: 1
-- takes: 16.067 ms
-- driver: mysql/0
-- 2010-07-17 11:48:28

OK: SELECT COUNT(*) FROM (SELECT * FROM `view_book_search`  WHERE 
			(`title` LIKE '%román%' OR
			`subtitle` LIKE '%román%' OR
			`name` LIKE '%román%' OR
			`first_name` LIKE '%román%' OR
			`last_name` LIKE '%román%' OR
			`group_name` LIKE '%román%') AND 
			(`title` LIKE '%fantasy%' OR
			`subtitle` LIKE '%fantasy%' OR
			`name` LIKE '%fantasy%' OR
			`first_name` LIKE '%fantasy%' OR
			`last_name` LIKE '%fantasy%' OR
			`group_name` LIKE '%fantasy%') GROUP BY `id_book`) t;
-- rows: 1
-- takes: 193.480 ms
-- driver: mysql/0
-- 2010-07-17 11:48:29

OK: SELECT * 
			FROM (SELECT * FROM `user` WHERE `id_user` = 445) t;
-- rows: 1
-- takes: 16.155 ms
-- driver: mysql/0
-- 2010-07-17 11:50:13

OK: SELECT COUNT(*) FROM (SELECT * FROM `view_book_search`  WHERE 
			(`title` LIKE '%román%' OR
			`subtitle` LIKE '%román%' OR
			`name` LIKE '%román%' OR
			`first_name` LIKE '%román%' OR
			`last_name` LIKE '%román%' OR
			`group_name` LIKE '%román%') AND 
			(`title` LIKE '%fantasy%' OR
			`subtitle` LIKE '%fantasy%' OR
			`name` LIKE '%fantasy%' OR
			`first_name` LIKE '%fantasy%' OR
			`last_name` LIKE '%fantasy%' OR
			`group_name` LIKE '%fantasy%') AND 
			(`title` LIKE '%sapkowski%' OR
			`subtitle` LIKE '%sapkowski%' OR
			`name` LIKE '%sapkowski%' OR
			`first_name` LIKE '%sapkowski%' OR
			`last_name` LIKE '%sapkowski%' OR
			`group_name` LIKE '%sapkowski%') AND 
			(`title` LIKE '%upíři%' OR
			`subtitle` LIKE '%upíři%' OR
			`name` LIKE '%upíři%' OR
			`first_name` LIKE '%upíři%' OR
			`last_name` LIKE '%upíři%' OR
			`group_name` LIKE '%upíři%') GROUP BY `id_book`) t;
-- rows: 1
-- takes: 170.006 ms
-- driver: mysql/0
-- 2010-07-17 11:50:13

OK: SELECT * 
			FROM (SELECT * FROM `user` WHERE `id_user` = 445) t;
-- rows: 1
-- takes: 16.076 ms
-- driver: mysql/0
-- 2010-07-17 11:50:23

OK: SELECT COUNT(*) FROM (SELECT * FROM `view_book_search`  WHERE 
			(`title` LIKE '%fantasy%' OR
			`subtitle` LIKE '%fantasy%' OR
			`name` LIKE '%fantasy%' OR
			`first_name` LIKE '%fantasy%' OR
			`last_name` LIKE '%fantasy%' OR
			`group_name` LIKE '%fantasy%') AND 
			(`title` LIKE '%sapkowski%' OR
			`subtitle` LIKE '%sapkowski%' OR
			`name` LIKE '%sapkowski%' OR
			`first_name` LIKE '%sapkowski%' OR
			`last_name` LIKE '%sapkowski%' OR
			`group_name` LIKE '%sapkowski%') GROUP BY `id_book`) t;
-- rows: 1
-- takes: 175.756 ms
-- driver: mysql/0
-- 2010-07-17 11:50:24

OK: SELECT * 
			FROM (SELECT * FROM `view_book_search`  WHERE 
			(`title` LIKE '%fantasy%' OR
			`subtitle` LIKE '%fantasy%' OR
			`name` LIKE '%fantasy%' OR
			`first_name` LIKE '%fantasy%' OR
			`last_name` LIKE '%fantasy%' OR
			`group_name` LIKE '%fantasy%') AND 
			(`title` LIKE '%sapkowski%' OR
			`subtitle` LIKE '%sapkowski%' OR
			`name` LIKE '%sapkowski%' OR
			`first_name` LIKE '%sapkowski%' OR
			`last_name` LIKE '%sapkowski%' OR
			`group_name` LIKE '%sapkowski%') GROUP BY `id_book`) t 
			 
			 
			  LIMIT 12;
-- rows: 9
-- takes: 177.141 ms
-- driver: mysql/0
-- 2010-07-17 11:50:24

OK: SELECT * 
			FROM (SELECT * FROM `view_book_author`) t 
			 WHERE (`id_book` IN (4, 5, 6, 7, 8, 505, 506, 1238, 1239));
-- rows: 9
-- takes: 61.305 ms
-- driver: mysql/0
-- 2010-07-17 11:50:24

OK: SELECT * 
			FROM (SELECT * FROM `user` WHERE `id_user` = 445) t;
-- rows: 1
-- takes: 19.286 ms
-- driver: mysql/0
-- 2010-07-17 11:52:02

OK: SELECT COUNT(*) FROM (SELECT * FROM `view_author`  WHERE 
			(`first_name` LIKE '%fantasy%' OR
			`last_name` LIKE '%fantasy%' OR
			`group_name` LIKE '%fantasy%') AND 
			(`first_name` LIKE '%sapkowski%' OR
			`last_name` LIKE '%sapkowski%' OR
			`group_name` LIKE '%sapkowski%') GROUP BY `id_author`) t;
-- rows: 1
-- takes: 19.744 ms
-- driver: mysql/0
-- 2010-07-17 11:52:02

OK: SELECT * 
			FROM (SELECT * FROM `user` WHERE `id_user` = 445) t;
-- rows: 1
-- takes: 16.199 ms
-- driver: mysql/0
-- 2010-07-17 11:52:04

OK: SELECT COUNT(*) FROM (
			 SELECT * FROM `view_post`
			 WHERE `content` LIKE '%fantasy sapkowski%'  OR `user_nick` LIKE '%fantasy sapkowski%'  OR `discussion_name` LIKE '%fantasy sapkowski%' ) t;
-- rows: 1
-- takes: 29.993 ms
-- driver: mysql/0
-- 2010-07-17 11:52:04

