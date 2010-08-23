DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
	`id_language` INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name` VARCHAR (50) NOT NULL COMMENT 'nazev jazyka',
	`locale` VARCHAR (10) NOT NULL COMMENT 'zkratka jazyka',
	`google` VARCHAR (10) NOT NULL COMMENT 'zkratka jazyka pro google (cs,en...)',
	UNIQUE (`name`), UNIQUE (`locale`)
) ENGINE = InnoDB COMMENT = 'Obsahuje pouzivane jazyky';

DROP TABLE IF EXISTS `domain`;
CREATE TABLE `domain` (
	`id_domain` INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT (25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je domena vedena',
	`uri` VARCHAR (50) NOT NULL COMMENT 'domena, na ktere web bezi',
	`email` VARCHAR(100) NOT NULL COMMENT 'e-mail webu',
	FOREIGN KEY (`id_language`) REFERENCES `language`(`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`uri`)
) ENGINE = InnoDB COMMENT = 'Jednotlive instance webu.';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id_user` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'preferovany jazyk uzivatele',
	`role` ENUM('common', 'moderator', 'admin') NOT NULL DEFAULT 'common' COMMENT 'role',
	`email` VARCHAR(255) NULL COMMENT 'e-mail',
	`password` VARCHAR(255) NULL COMMENT 'hash hesla',
	`nick` VARCHAR(255) NOT NULL COMMENT 'prezdivka, pod kterou uzivatel vystupuje',
	`sex` ENUM('male','female') NULL COMMENT 'pohlavi',
	`birth_year` INT(3) NULL COMMENT 'vek',
	`last_logged` DATETIME NULL COMMENT 'cas, kdy byl uzivatel naposled prihlasen',
	`new_pass_key` VARCHAR(30) NULL COMMENT 'hash pro zaslani noveho hesla',
	`new_pass_time` DATETIME NULL COMMENT 'cas vygenerovani hashe pro nove heslo',
	`about` TEXT NULL COMMENT 'kratky text o uzivateli',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`email`),
	UNIQUE(`nick`),
	INDEX(`nick`)
) ENGINE = InnoDB COMMENT = 'uzivatele';

DROP TABLE IF EXISTS `connection`;
CREATE TABLE `connection` (
	`id_connection` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel',
	`type`	ENUM('facebook','twitter') NOT NULL COMMENT 'typ propojeni - sluzba na ktere se autorizuje',
	`token` TEXT NOT NULL COMMENT 'cizi klic, ktery je dostupny - hash, id uzivatele na cizim serveru...',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`id_user`,`type`)
) ENGINE = InnoDB COMMENT = 'propojeni s ucty uzivatelu na jinych serverech, napriklad facebook';

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
	`id_book` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'knihy nezavisle na lokalizaci';

DROP TABLE IF EXISTS `book_title`;
CREATE TABLE `book_title` (
	`id_book_title` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, k niz se titul vztahuje',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je kniha napsana',
	`title` VARCHAR(255) NOT NULL COMMENT 'titul knihy',
	`subtitle` VARCHAR(255) NULL COMMENT 'podtitul knihy',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knizni tituly v jiz lokalizovanych nazvech';

DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
	`id_author` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`type` ENUM ('person', 'group') NOT NULL DEFAULT 'person',
	`first_name` VARCHAR(255) NULL COMMENT 'krestni jmeno autora, vyplneno v pripade, ze type = person',
	`last_name` VARCHAR(255) NULL COMMENT 'prijmeni autora, vyplneno v pripade, ze type = person',
	`group_name` VARCHAR(255) NULL COMMENT 'nazev skupiny, vyplneno v pripade, ze type = group',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'spisovatele - lide, skupiny';

DROP TABLE IF EXISTS `written_by`;
CREATE TABLE `written_by` (
	`id_written_by` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'napsana kniha',
	`id_author` INT(25) UNSIGNED NOT NULL COMMENT 'spisovatel, ktery knihu napsal',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`id_book`, `id_author`)
) ENGINE = InnoDB COMMENT = 'vztah mezi spisovateli a knihami';

DROP TABLE IF EXISTS `shelf`;
CREATE TABLE `shelf` (
	`id_shelf` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user` INT(25) UNSIGNED NOT NULL,
	`type` ENUM('general','read','wanted','owned') NOT NULL DEFAULT 'general' COMMENT 'typ policky - obecna, precteno, poptavano, vlastneno',
	`name` VARCHAR(255) NULL COMMENT 'nazev policky',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'uzivatelovi policky, do kterych si pridava knihy';

DROP TABLE IF EXISTS `in_shelf`;
CREATE TABLE `in_shelf` (
	`id_in_shelf` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_shelf` INT(25) UNSIGNED NOT NULL COMMENT 'police, ve ktere je kniha ulozena',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha v polici',
	`order` INT(25) UNSIGNED NULL COMMENT 'poradi knihy v policce; pokud je NULL, je nezarazena',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_shelf`) REFERENCES `shelf` (`id_shelf`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`id_book`,`id_shelf`)
) ENGINE = InnoDB COMMENT = 'ulozene knihy v uzivetelskych policich';

DROP TABLE IF EXISTS `opinion`;
CREATE TABLE `opinion` (
	`id_opinion` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery nazor napsal',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, kterym je nazor napsany',
	`id_book_title` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, ke ktere je nazor napsany',
	`rating` ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT 'hodnoceni',
	`content` TEXT NOT NULL COMMENT 'slovni vyjadreni nazoru na knihu',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book_title`) REFERENCES `book_title` (`id_book_title`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`id_user`,`id_book_title`),
	INDEX(`id_user`),
	INDEX(`id_book_title`)
) ENGINE = InnoDB COMMENT = 'hodnocene nazory uzivatelu na knihy';

DROP TABLE IF EXISTS topic;
CREATE TABLE topic (
	`id_topic` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu patri diskusni prispevek',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev diskusniho tematu',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'diskusni temata';


DROP TABLE IF EXISTS discussable;
CREATE TABLE discussable (
	`id_discussable` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`table` VARCHAR(100) NOT NULL COMMENT 'nazev tabulky, ktera obsahuje entity, ktere mohou byt diskutovany',
	`column_id` VARCHAR(100) NOT NULL COMMENT 'nazev sloupce, ktery obsahuje ID entity',
	`column_name` VARCHAR(255) NOT NULL COMMENT 'nazev sloupce, ze ktereho se bere nazev diskuse',
	`column_subname` VARCHAR(255) NULL COMMENT 'nazev sloupce, ze ktereho se bere pripadny podnazev diskuse',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'typy entit, ke kterym mohou byt vedeny diskuse';

DROP TABLE IF EXISTS discussion;
CREATE TABLE discussion (
	`id_discussion` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_discussable` INT(25) UNSIGNED NOT NULL COMMENT 'typ entity, k niz se diskuse vede',
	`id_discussed` INT(25) UNSIGNED NOT NULL COMMENT 'ID entity, ke ktere se diskuse vede',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev diskuse',
	`subname` VARCHAR(255) NULL COMMENT 'podnazev diskuse',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_discussable`) REFERENCES `discussable`(`id_discussable`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'odkazy na diskutovane entity, tvori vlakno diskuse';

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
	`id_post` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_discussion` INT(25) UNSIGNED NOT NULL COMMENT 'odkaz na entitu, ke ktere je vedena diskuse',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu patri diskusni prispevek',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je prispevek napsan',
	`reply` INT(25) UNSIGNED NULL COMMENT 'diskusni prispevek, na ktery tento prispevek reaguje',
	`subject` VARCHAR(255) NULL COMMENT 'predmet diskusniho prispevku',
	`content` TEXT NOT NULL COMMENT 'obsah diskusniho prispevku',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_discussion`) REFERENCES `discussion` (`id_discussion`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`reply`) REFERENCES `discussion` (`id_discussion`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'diskusni prispevky';

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
	`id_tag` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, do nehoz klicove slovo patri',
	`name` VARCHAR(255) NOT NULL COMMENT 'samotne klicove slovo',
	`updated` TIMESTAMP NOT NULL COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'klicova slova pouzita k oznaceni knih';

DROP TABLE IF EXISTS `tagged`;
CREATE TABLE `tagged` (
	`id_tagged` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_tag` INT(25) UNSIGNED NOT NULL COMMENT 'klicove slovo',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'oznacena kniha',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`id_tag`, `id_book`),
	INDEX(`id_tag`),
	INDEX(`id_book`)
) ENGINE = InnoDB COMMENT = 'vztah mezi knihami a klicovymi slovy';

DROP TABLE IF EXISTS `book_similarity`;
CREATE TABLE `book_similarity` (
	`id_book_similarity` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book_from` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, u ktere hledam podobnost',
	`id_book_to` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, u ktere je zavedena podobnost',
	`value` DECIMAL(5,2) NOT NULL COMMENT 'hodnota podobnosti',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_book_from`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book_to`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'podobnost knih';

DROP TABLE IF EXISTS `user_similarity`;
CREATE TABLE `user_similarity` (
	`id_user_similarity` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user_from` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, u ktere hledam podobnost',
	`id_user_to` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, u ktere je zavedena podobnost',
	`value` DECIMAL(5,2) NOT NULL COMMENT 'hodnota podobnosti',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user_from`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_user_to`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'podobnost uzivatelu';

DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
	`id_help` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, k nemuz napoveda patri',
	`category` ENUM('book','author','user','other') NOT NULL COMMENT 'kategorie napovedy',
	`text` TEXT NOT NULL,
	`image` TEXT NULL,
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'vestavena napoveda';

DROP TABLE IF EXISTS `edition`;
CREATE TABLE `edition` (
	`id_edition` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book_title` INT(25) UNSIGNED NOT NULL COMMENT 'knizni titul',
	`isbn10` VARCHAR(100) NULL COMMENT 'isbn 10',
	`isbn13` VARCHAR(100) NULL COMMENT 'isbn 13',
	`pages` INT(25) UNSIGNED NULL COMMENT 'pocet stran',
	`published` VARCHAR(4) NULL COMMENT 'rok vydani',
	`image` VARCHAR(255) NULL COMMENT 'soubor obsahujici obrazek obalky knihy',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_book_title`) REFERENCES `book_title` (`id_book_title`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`isbn10`),
	UNIQUE(`isbn13`),
	INDEX(`id_book_title`)
) ENGINE = InnoDB COMMENT = 'Vydani knih';

DROP TABLE IF EXISTS `following`;
CREATE TABLE `following` (
    `id_following` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
    `id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery followuje',
    `id_user_followed` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery je followan',
    FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`id_user_followed`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE(`id_user`, `id_user_followed`)
) ENGINE = InnoDB COMMENT = 'followers';

DROP TABLE IF EXISTS `support_category`;
CREATE TABLE `support_category` (
	`id_support_category` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'preferovany jazyk uzivatele',
	`name` VARCHAR(255) NOT NULL COMMENT 'jmeno kategorie v danem jazyce',
	`description` TEXT NOT NULL COMMENT 'kratky popis kategorie',
	`weight` TINYINT NOT NULL COMMENT 'tiha dane kategorie (aby slo urcit poradi)',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'kategorie napovedy';

DROP TABLE IF EXISTS `support_text`;
CREATE TABLE `support_text` (
	`id_support_text` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_support_category` INT(25) UNSIGNED NOT NULL COMMENT 'category',
	`name` VARCHAR(255) NOT NULL COMMENT 'jmeno kategorie v danem jazyce',
	`text` TEXT NOT NULL COMMENT 'text dane stranky napovedy',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	`weight` TINYINT NOT NULL COMMENT 'tiha dane kategorie (aby slo urcit poradi)',
	FOREIGN KEY (`id_support_category`) REFERENCES `support_category` (`id_support_category`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'texty napovedy';

DROP TABLE IF EXISTS `captcha`;
CREATE TABLE `captcha` (
	`id_captcha` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'id captchy',
	`question` TEXT NOT NULL COMMENT 'dokonala otazka',
	`answer` TEXT NOT NULL COMMENT 'jedina odpoved',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'preferovany jazyk uzivatele',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'Tabulka s textovymi otazkami pro captchu.';

DROP TABLE IF EXISTS `user_log`;
CREATE TABLE `user_log` (
	`id_log` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`id_user` INT UNSIGNED NOT NULL ,
	`url` TEXT NOT NULL ,
	`text` TEXT NOT NULL ,
	`time` DATETIME NOT NULL,
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = MYISAM COMMENT = 'Table with user actions log' 