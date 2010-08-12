
--
-- Struktura tabulky `support_category`
--

CREATE TABLE IF NOT EXISTS `support_category` (
  `id_support_category` int(25) unsigned NOT NULL auto_increment COMMENT 'identifikator',
  `id_language` int(25) unsigned NOT NULL COMMENT 'preferovany jazyk uzivatele',
  `name` varchar(255) collate utf8_czech_ci NOT NULL COMMENT 'jmeno kategorie v danem jazyce',
  `description` text collate utf8_czech_ci NOT NULL COMMENT 'kratky popis kategorie',
  `weight` tinyint(4) NOT NULL COMMENT 'tiha dane kategorie (aby slo urcit poradi)',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
  PRIMARY KEY  (`id_support_category`),
  KEY `id_language` (`id_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='kategorie napovedy' AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `support_category`
--

INSERT INTO `support_category` (`id_support_category`, `id_language`, `name`, `description`, `weight`, `updated`) VALUES
(1, 2, 'About project', 'Our purpose, filosofy, principes of this website, terms.', 1, '2010-08-06 10:34:18'),
(2, 2, 'Actions', 'Short tutorials how to do most of actions on our website', 2, '2010-08-06 10:34:53'),
(3, 2, 'Frequently asked questions', 'What to do if something unexpected happened.', 3, '2010-08-06 10:35:20'),
(4, 2, 'Terms and privacy', 'Long and boring text about terms of using this service and protecting your privacy.', 4, '2010-08-06 10:36:08'),
(5, 2, 'For fans and developers', 'Where everything else stays.', 5, '2010-08-06 10:36:22');

-- --------------------------------------------------------

--
-- Struktura tabulky `support_text`
--

CREATE TABLE IF NOT EXISTS `support_text` (
  `id_support_text` int(25) unsigned NOT NULL auto_increment COMMENT 'identifikator',
  `id_support_category` int(25) unsigned NOT NULL COMMENT 'category',
  `name` varchar(255) collate utf8_czech_ci NOT NULL COMMENT 'jmeno kategorie v danem jazyce',
  `text` text collate utf8_czech_ci NOT NULL COMMENT 'text dane stranky napovedy',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
  `weight` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_support_text`),
  KEY `id_support_category` (`id_support_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='texty napovedy' AUTO_INCREMENT=34 ;

--
-- Vypisuji data pro tabulku `support_text`
--

INSERT INTO `support_text` (`id_support_text`, `id_support_category`, `name`, `text`, `updated`, `weight`) VALUES
(1, 1, 'Principes and goals', 'TBD: Proč vznikl, jaký je cíl, principy fungování (podobnosti...).', '2010-08-12 11:16:20', 1),
(2, 1, 'Conception (dictionary)', 'TBD: \r\nVysvětlit některé pojmy\r\n     > Čtenářský deník\r\n     > Kniha, edice, autor, klíčová slova...\r\n     > Hodnocení, názor\r\n     > Poličky\r\n     > Následovatelé/následníci', '2010-08-12 11:18:07', 2),
(3, 1, 'Orientation on main page', 'TBD', '2010-08-12 11:18:07', 3),
(4, 1, 'Orientation on page of book', 'TBD', '2010-08-12 11:18:39', 4),
(5, 1, 'Orientation on feed', 'TBD', '2010-08-12 11:18:39', 5),
(6, 1, 'Orientation on page of user', 'TBD', '2010-08-12 11:19:05', 6),
(7, 2, 'Registration', 'TBD', '2010-08-12 11:19:51', 1),
(8, 2, 'Login', 'TBD', '2010-08-12 11:19:51', 2),
(9, 2, 'Login via Facebook or Twitter', 'TBD', '2010-08-12 11:20:31', 3),
(10, 2, 'Forgotten password', 'TBD', '2010-08-12 11:20:31', 4),
(11, 2, 'Logout', 'TBD', '2010-08-12 11:21:00', 5),
(12, 2, 'Changing user settings', 'TBD', '2010-08-12 11:21:00', 6),
(13, 2, 'Searching', 'TBD', '2010-08-12 11:21:53', 7),
(14, 2, 'Inserting or updating opinion', 'TBD', '2010-08-12 11:21:53', 8),
(15, 2, 'Inserting related book', 'TBD', '2010-08-12 11:22:46', 9),
(16, 2, 'Manual edition insertion', 'TBD', '2010-08-12 11:22:46', 10),
(17, 2, 'Using shelves', 'TBD', '2010-08-12 11:23:21', 11),
(18, 2, 'Using sharing to social networks', 'TBD', '2010-08-12 11:23:21', 12),
(19, 2, 'Inserting new book', 'TBD', '2010-08-12 11:23:47', 13),
(20, 2, 'Inserting new author', 'TBD', '2010-08-12 11:23:47', 14),
(21, 2, 'Using discussion', 'TBD', '2010-08-12 11:24:27', 15),
(22, 2, 'Following users', 'TBD', '2010-08-12 11:24:27', 16),
(23, 2, 'Adding user icon to your page', 'TBD', '2010-08-12 11:24:59', 17),
(24, 3, 'I forgot my password', 'TBD', '2010-08-12 11:25:49', 1),
(25, 3, 'I cannot login', 'TBD', '2010-08-12 11:25:49', 2),
(26, 3, 'I want to delete my account', 'TBD', '2010-08-12 11:26:33', 3),
(27, 3, 'Something is not working', 'TBD', '2010-08-12 11:26:33', 4),
(28, 3, 'Error, spam or abuse reporting', 'TBD', '2010-08-12 11:27:06', 5),
(29, 4, 'Terms of Use', 'TBD', '2010-08-12 11:31:12', 1),
(30, 4, 'Privacy declaration', 'TBD', '2010-08-12 11:28:11', 2),
(31, 4, 'Good manners', 'TBD', '2010-08-12 11:30:52', 3),
(32, 5, 'How to join our affords', 'TBD', '2010-08-12 11:32:09', 1),
(33, 5, 'API documentation', 'TBD', '2010-08-12 11:32:09', 2);

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `support_category`
--
ALTER TABLE `support_category`
  ADD CONSTRAINT `support_category_ibfk_1` FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `support_text`
--
ALTER TABLE `support_text`
  ADD CONSTRAINT `support_text_ibfk_1` FOREIGN KEY (`id_support_category`) REFERENCES `support_category` (`id_support_category`) ON DELETE CASCADE ON UPDATE CASCADE;