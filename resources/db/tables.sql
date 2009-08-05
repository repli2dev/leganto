-- TABULKY TYKAJICI SE UZIVATELU
-- ------------------------------------
DROP TABLE IF EXISTS `filter`;
CREATE TABLE `filter` (
	`id_filter` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT
'identifikator',
	`id_user` INT (25) UNSIGNED NOT NULL COMMENT 'uzivatel, jemuz filter patri',
	`enabled` TINYINT (1) UNSIGNED NOT NULL COMMENT 'stav filtru',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE
CASCADE
) ENGINE = InnoDB COMMENT = 'fitry, ktere uzivatele pouzivaji na sve domovske strance';

DROP TABLE IF EXISTS `rule`;
CREATE TABLE `rule` (
	`id_rule` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_filter` INT (25) UNSIGNED NOT NULL COMMENT 'filter, ke kteremu pravidlo patri',
	`id_restriction` INT (25) UNSIGNED NOT NULL COMMENT 'typ omezeni, ktery uplatnuje
toto pravidlo',
	FOREIGN KEY (`id_filter`) REFERENCES `filter` (`id_filter`) ON UPDATE CASCADE ON
DELETE CASCADE,
	FOREIGN KEY (`id_restriction`) REFERENCES `restriction` (`id_restriction`) ON UPDATE
CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'pravidla, ktera uzivatel uplatnuje ve svych filtrech';

DROP TABLE IF EXISTS `restriction`;
CREATE TABLE `restriction` (
	`id_restriction` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT
'identifikator',
	`id_module` INT (25) UNSIGNED NOT NULL COMMENT 'modul, nad kterym je omezeni
definovano',
	`class` TEXT NOT NULL COMMENT 'model, komponenta, ktera bude akci zarizovat',
	`method` TEXT NOT NULL COMMENT 'metoda, ktera provadi akci, ci vrati cast dotazu',
	FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON UPDATE CASCADE ON
DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'jednotlive typy omezeni - jsou dane moduly samotnymi';


-- TABULKY TYKAJICI SE VZTAHU UZIVATELU A KNIH
-- ------------------------------------

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
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_shelf`) REFERENCES `shelf` (`id_shelf`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'ulozene knihy v uzivetelskych policich';

DROP TABLE IF EXISTS `opinion`;
CREATE TABLE `opinion` (
	`id_opinion` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery nazor napsal',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, kterym je nazor napsany',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, ke ktere je nazor napsany',
	`rating` ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT 'hodnoceni',
	`content` TEXT NOT NULL COMMENT 'slovni vyjadreni nazoru na knihu',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'hodnocene nazory uzivatelu na knihy';

-- TABULKY TYKAJICI SE DISKUSI
-- ------------------------------------
DROP TABLE IF EXISTS discussed;
CREATE TABLE discussed (
	`id_discussed` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_module_table` INT(25) UNSIGNED NOT NULL COMMENT 'typ entity, k niz se diskuse vede',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev diskuse',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (id_module_table) REFERENCES module_table(id_module_table) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'odkazy na diskutovane entity, tvori vlakno diskuse';

DROP TABLE IF EXISTS `discussion`;
CREATE TABLE `discussion` (
	`id_discussion` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_discussed` INT(25) UNSIGNED NOT NULL COMMENT 'odkaz na entitu, ke ktere je vedena diskuse',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu patri diskusni prispevek',
	`reply` INT(25) UNSIGNED NULL COMMENT 'diskusni prispevek, na ktery tento prispevek reaguje',
	`subject` VARCHAR(255) NULL COMMENT 'predmet diskusniho prispevku',
	`content` TEXT NOT NULL COMMENT 'obsah diskusniho prispevku',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_discussed`) REFERENCES `discussed` (`id_discussed`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`reply`) REFERENCES `discussion` (`id_discussion`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'diskusni prispevky';

-- TABULKY TYKAJICI SE KLICOVYCH SLOV
-- ------------------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE tag (
	`id_tag` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, do nehoz klicove slovo patri',
	`name` VARCHAR(255) NOT NULL COMMENT 'samotne klicove slovo',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'klicova slova pouzita k oznaceni knih';

DROP TABLE IF EXISTS `tagged`;
CREATE TABLE `tagged` (
	`id_tagged` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_tag` INT(25) UNSIGNED NOT NULL COMMENT 'klicove slovo',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'klicove slovo',
	FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'vztah mezi knihami a klicovymi slovy'
