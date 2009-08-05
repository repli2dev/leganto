DROP TABLE IF EXISTS `set`;
CREATE TABLE `set` (
	`id_set` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'knizni set';

DROP TABLE IF EXISTS `set_name`;
CREATE TABLE `set_name` (
	id_set_name INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_set INT(25) UNSIGNED NOT NULL COMMENT 'set, k niz se nazev vztahuje',
	id_language INT(25) UNSIGNED NOT NULL COMMENT 'jazyk lokalizovaneho nazvu',
	name VARCHAR(255) NOT NULL COMMENT 'lokalizovany nazev set',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_set`) REFERENCES `set` (`id_set`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'lokalizovane nazvy kniznich serii';

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
	`id_book` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_set` INT(25) UNSIGNED NULL COMMENT 'set k niz kniha prislusi (jen pokud k nejake prislusi, jinak NULL)',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'knihy nezavisle na lokalizaci';

DROP TABLE IF EXISTS `in_set`;
CREATE TABLE `in_set` (
	`id_in_set` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha',
	`id_set` INT(25) UNSIGNED NOT NULL COMMENT 'set',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_set`) REFERENCES `set` (`id_set`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'zavislost knih a serii - byt v serii';

DROP TABLE IF EXISTS `book_title`;
CREATE TABLE `book_title` (
	`id_book_title` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, k niz se titul vztahuje',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je kniha napsana',
	`title` VARCHAR(255) NOT NULL COMMENT 'titul knihy',
	`subtitle` VARCHAR(255) NULL COMMENT 'podtitul knihy',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knizni tituly v jiz lokalizovanych nazvech';

DROP TABLE IF EXISTS `writer`;
CREATE TABLE `writer` (
	`id_writer` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`type` ENUM ('person', 'group') NOT NULL DEFAULT 'person',
	`first_name` VARCHAR(255) NULL COMMENT 'krestni jmeno autora, vyplneno v pripade, ze type = person',
	`last_name` VARCHAR(255) NULL COMMENT 'prijmeni autora, vyplneno v pripade, ze type = person',
	`group_name` VARCHAR(255) NULL COMMENT 'nazev skupiny, vyplneno v pripade, ze type = group',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'spisovatele - lide, skupiny';

DROP TABLE IF EXISTS `written_by`;
CREATE TABLE `written_by` (
	`id_written_by` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'napsana kniha',
	`id_writer` INT(25) UNSIGNED NOT NULL COMMENT 'spisovatel, ktery knihu napsal',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_writer`) REFERENCES `writer` (`id_writer`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'vztah mezi spisovateli a knihami';

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE `publisher` (
	`id_publisher` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev nakladatelstvi',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'vydavatelstvi';

DROP TABLE IF EXISTS `edition`;
CREATE TABLE `edition` (
	`id_edition` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, jejiz vydani je ulozeno',
	`id_publisher` INT(25) UNSIGNED NOT NULL COMMENT 'vydavatelstvi',
	`isbn` VARCHAR(30) NOT NULL COMMENT 'ISBN vydani',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_publisher`) REFERENCES `publisher` (`id_publisher`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knizni vydani';