--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id_language`, `name`, `locale`, `google`) VALUES
(1, 'Česky', 'cs_CZ', 'cs'),
(2, 'English', 'en_US', 'en');

--
-- Dumping data for table `domain`
--

INSERT INTO `domain` (`id_domain`, `id_language`, `uri`, `email`) VALUES
(1, 1, 'preader', ''),
(2, 1, 'leganto', '');

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id_author`, `type`, `first_name`, `last_name`, `group_name`, `inserted`, `updated`) VALUES
(1, 'person', 'George', 'Orwell', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(2, 'person', 'Arthur Charles', 'Clarke', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(3, 'person', 'Andrzej', 'Sapkowski', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(4, 'person', 'Vladislav', 'Vančura', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(5, 'person', 'Karel', 'Čapek', NULL, '2010-11-20 17:11:33', '2010-11-20 17:36:31'),
(6, 'person', 'Orla', 'Melling', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(7, 'person', 'George', 'Orwell', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(8, 'person', 'Ota', 'Pavel', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(10, 'person', 'Zdeněk', 'Jirotka', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(11, 'person', 'Edgar Allan', 'Poe', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(12, 'person', 'Charles', 'Dickens', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(13, 'person', 'Agatha', 'Christie', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(14, 'person', 'Phyllis Dorothy', 'Jamesová', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(15, 'person', 'Lion', 'Feuchtwanger', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(16, 'person', 'Henryk', 'Sienkiewicz', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(17, 'person', 'Mika', 'Waltari', NULL, '2010-11-20 17:11:33', '2010-11-20 17:11:02'),
(18, 'person', 'Henryk', 'Sienkiewicz', NULL, '2010-11-20 17:11:33', '2010-11-20 17:36:32'),
(19, 'person', 'Radka', 'Denemarková', NULL, '2010-11-20 17:11:34', '2010-11-20 17:11:03'),
(20, 'person', 'Umberto', 'Eco', NULL, '2010-11-20 17:11:34', '2010-11-20 17:11:03');

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id_book`, `inserted`, `updated`) VALUES
(1, '2007-09-24 19:10:11', '2010-11-20 17:11:31'),
(2, '2007-09-25 16:55:37', '2010-11-20 17:11:31'),
(3, '2007-09-25 16:57:01', '2010-11-20 17:11:31'),
(4, '2007-09-25 17:01:31', '2010-11-20 17:11:31'),
(5, '2007-09-25 17:06:16', '2010-11-20 17:11:31'),
(6, '2007-09-25 17:09:49', '2010-11-20 17:11:31'),
(7, '2007-09-25 17:17:48', '2010-11-20 17:11:31'),
(8, '2007-09-25 17:26:24', '2010-11-20 17:11:31'),
(9, '2007-10-02 11:49:31', '2010-11-20 17:11:32'),
(10, '2007-10-15 18:48:40', '2010-11-20 17:11:32'),
(11, '2007-10-23 13:21:28', '2010-11-20 17:11:32'),
(13, '2007-10-24 18:00:40', '2010-11-20 17:11:32'),
(14, '2007-10-25 22:01:29', '2010-11-20 17:11:32'),
(15, '2007-10-26 17:12:48', '2010-11-20 17:11:32'),
(16, '2007-10-26 17:14:55', '2010-11-20 17:11:32'),
(17, '2007-10-27 18:39:45', '2010-11-20 17:11:32'),
(18, '2007-10-27 18:42:42', '2010-11-20 17:11:32'),
(19, '2007-10-28 15:09:40', '2010-11-20 17:11:32'),
(20, '2007-10-28 15:14:29', '2010-11-20 17:11:32'),
(21, '2007-10-28 15:16:09', '2010-11-20 17:11:32'),
(22, '2007-10-28 18:23:41', '2010-11-20 17:11:32'),
(23, '2007-10-28 18:25:42', '2010-11-20 17:11:32'),
(24, '2007-10-28 18:27:30', '2010-11-20 17:11:34'),
(25, '2007-10-28 18:29:01', '2010-11-20 17:11:34'),
(26, '2007-10-29 18:12:18', '2010-11-20 17:11:34'),
(27, '2007-10-30 21:06:54', '2010-11-20 17:11:34'),
(28, '2007-10-30 21:13:04', '2010-11-20 17:11:34');

--
-- Dumping data for table `book_title`
--

INSERT INTO `book_title` (`id_book_title`, `id_book`, `id_language`, `title`, `subtitle`, `inserted`, `updated`) VALUES
(1, 1, 1, '1984', NULL, '2007-09-24 19:10:11', '2010-11-20 17:11:31'),
(2, 2, 1, '2001: Vesmírná odysea', NULL, '2007-09-25 16:55:37', '2010-11-20 17:11:31'),
(3, 3, 1, '2010: Druhá vesmírná odysea', NULL, '2007-09-25 16:57:01', '2010-11-20 17:11:31'),
(4, 4, 1, 'Krev elfů: první část ságy o Geraltovi a Ciri', NULL, '2007-09-25 17:01:31', '2010-11-22 11:09:14'),
(5, 5, 1, 'Čas opovržení', NULL, '2007-09-25 17:06:16', '2010-11-20 17:11:31'),
(6, 6, 1, 'Křest ohněm: třetí část ságy o Geraltovi a Ciri', NULL, '2007-09-25 17:09:49', '2010-11-22 11:08:48'),
(7, 7, 1, 'Věž vlaštovky: čtvrtá část ságy o zaklínači', NULL, '2007-09-25 17:17:48', '2010-11-22 11:10:26'),
(8, 8, 1, 'Paní jezera: pátá část ságy o zaklínači', NULL, '2007-09-25 17:26:24', '2010-11-22 11:11:20'),
(9, 9, 1, 'Rozmarné léto', NULL, '2007-10-02 11:49:31', '2010-11-20 17:11:32'),
(10, 10, 1, 'Krakatit', NULL, '2007-10-15 18:48:40', '2010-11-20 17:11:32'),
(11, 11, 1, 'Druidova píseň', NULL, '2007-10-23 13:21:28', '2010-11-20 17:11:32'),
(13, 13, 1, 'Jak jsem potkal ryby', NULL, '2007-10-24 18:00:40', '2010-11-20 17:11:32'),
(14, 14, 1, 'Noční hlídka', NULL, '2007-10-25 22:01:29', '2010-11-20 17:11:32'),
(15, 15, 1, 'Saturnin', NULL, '2007-10-26 17:12:48', '2010-11-20 17:11:32'),
(16, 16, 1, 'Havran', NULL, '2007-10-26 17:14:55', '2010-11-20 17:11:32'),
(17, 17, 1, 'Ponurý dům', NULL, '2007-10-27 18:39:45', '2010-11-20 17:11:32'),
(18, 18, 1, 'Kronika Pickwickova klubu', NULL, '2007-10-27 18:42:42', '2010-11-20 17:11:32'),
(19, 19, 1, 'Vraždy podle abecedy', NULL, '2007-10-28 15:09:40', '2010-11-20 17:11:32'),
(20, 20, 1, 'Vraždy v nakladatelství', NULL, '2007-10-28 15:14:29', '2010-11-20 17:11:32'),
(21, 21, 1, 'Zkouška neviny', NULL, '2007-10-28 15:16:09', '2010-11-20 17:11:32'),
(22, 22, 1, 'Židovka z Toleda', NULL, '2007-10-28 18:23:41', '2010-11-20 17:11:32'),
(23, 23, 1, 'Ošklivá vévodkyně', NULL, '2007-10-28 18:25:42', '2010-11-20 17:11:33'),
(24, 24, 1, 'Quo Vadis?', NULL, '2007-10-28 18:27:30', '2010-11-20 17:11:34'),
(25, 25, 1, 'Pařížská kravata', NULL, '2007-10-28 18:29:01', '2010-11-20 17:11:34'),
(26, 26, 1, 'Quo Vadis?', NULL, '2007-10-29 18:12:18', '2010-11-20 17:11:34'),
(27, 27, 1, 'Peníze od Hitlera - Letní mozaika', NULL, '2007-10-30 21:06:54', '2010-11-20 17:11:34'),
(28, 28, 1, 'A já pořád, kdo to tluče', NULL, '2007-10-30 21:13:04', '2010-11-20 17:11:34');

--
-- Dumping data for table `written_by`
--

INSERT INTO `written_by` (`id_written_by`, `id_book`, `id_author`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2),
(4265, 4, 3),
(5, 5, 3),
(4264, 6, 3),
(4266, 7, 3),
(4267, 8, 3),
(9, 9, 4),
(10, 10, 5),
(11, 11, 6),
(12, 13, 8),
(13, 14, 619),
(4271, 15, 10),
(15, 16, 11),
(16, 17, 12),
(17, 18, 12),
(18, 19, 13),
(19, 20, 14),
(20, 21, 13),
(21, 22, 15),
(22, 23, 15),
(23, 24, 16),
(24, 25, 17),
(25, 26, 18),
(26, 27, 19),
(27, 28, 19);