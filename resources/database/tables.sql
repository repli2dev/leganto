DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
	`id_language` INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name` VARCHAR (50) NOT NULL COMMENT 'nazev jazyka',
	`locale` VARCHAR (10) NOT NULL COMMENT 'zkratka jazyka',
	UNIQUE (`name`), UNIQUE (`locale`)
) ENGINE = InnoDB COMMENT = 'Obsahuje pouzivane jazyky';

DROP TABLE IF EXISTS `domain`;
CREATE TABLE `domain` (
	`id_domain` INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT (25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je domena vedena',
	`id_role` INT (25) UNSIGNED NOT NULL COMMENT 'defaultni role, ktera se dava uzivatelum pri registraci',
	`uri` VARCHAR (50) NOT NULL COMMENT 'domena, na ktere web bezi',
	`email` VARCHAR(100) NOT NULL COMMENT 'e-mail webu',
	FOREIGN KEY (`id_language`) REFERENCES `language`(`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`uri`)
) ENGINE = InnoDB COMMENT = 'Jednotlive instance webu.';

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
	`id_role` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name` VARCHAR(100) NOT NULL UNIQUE
) ENGINE = InnoDB COMMENT = 'role uzivatelu v systemu';

DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
	`id_permission` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_role` INT(25) UNSIGNED NOT NULL COMMENT 'role, ke ktere se pristupova prava vztahuji',
	`id_module` INT(25) UNSIGNED NOT NULL COMMENT 'modul, ke ktere se pristupova prava vztahuji',
	`action` ENUM('read','read_all','edit','edit_all','insert') NULL COMMENT 'typ pristupne akce na danem zdroji, pokud je NULL, jsou na danem zdroji pristupne vsechny akce',
	FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_module`) REFERENCES `module`(`id_module`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`id_role`,`id_module`)
) ENGINE = InnoDB COMMENT = 'pristupova prava roli ke zdrojum';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id_user` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'preferovany jazyk uzivatele',
	`id_role` INT(25) UNSIGNED NOT NULL COMMENT 'role, kterou uzivatel zastava v systemu',
	`email` VARCHAR(255) NOT NULL COMMENT 'e-mail',
	`password` VARCHAR(255) NOT NULL COMMENT 'hash hesla',
	`type` ENUM('root','common') NULL DEFAULT 'common' COMMENT 'typ uzivatele; common - kontroluji se pristupova prava, root - nekontroluji ',
	`nick` VARCHAR(255) NOT NULL COMMENT 'prezdivka, pod kterou uzivatel vystupuje',
	`sex` ENUM('male','female') NULL COMMENT 'pohlavi',
	`birth_year` INT(3) NULL COMMENT 'vek',
	`last_logged` DATETIME NULL COMMENT 'cas, kdy byl uzivatel naposled prihlasen',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`email`),
	UNIQUE(`nick`)
) ENGINE = InnoDB COMMENT = 'uzivatele';

DROP TABLE IF EXISTS `friendship`;
CREATE TABLE `friendship` (
	`id_friendship` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user_from` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery nabidl pratelstvi',
	`id_user_to` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu bylo nabidnuto ptatelstvi',
	`confirmed` ENUM('yes','no') NOT NULL DEFAULT 'no' COMMENT 'informace o tom, zda bylo pratelstvi potvrzeno',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	FOREIGN KEY (`id_user_from`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_user_to`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'pratelske vztahy mezi uzivateli';

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
	`id_status` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user` INT (25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu status nalezi',
	`id_language` INT (25) UNSIGNED NOT NULL COMMENT 'jazyk, kterym je status napsany',
	`content` TINYTEXT NOT NULL COMMENT 'status uzivatele',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'statusy uzivatelu';

DROP TABLE IF EXISTS `set`;
CREATE TABLE `set` (
	`id_set` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'knizni set';

DROP TABLE IF EXISTS `set_name`;
CREATE TABLE `set_name` (
	id_set_name INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_set INT(25) UNSIGNED NOT NULL COMMENT 'set, k niz se nazev vztahuje',
	id_language INT(25) UNSIGNED NOT NULL COMMENT 'jazyk lokalizovaneho nazvu',
	`name` VARCHAR(255) NOT NULL COMMENT 'lokalizovany nazev set',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_set`) REFERENCES `set` (`id_set`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'lokalizovane nazvy kniznich serii';

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
	`id_book` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
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

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE `publisher` (
	`id_publisher` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev nakladatelstvi',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'vydavatelstvi';

DROP TABLE IF EXISTS `edition`;
CREATE TABLE `edition` (
	`id_edition` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'kniha, jejiz vydani je ulozeno',
	`id_publisher` INT(25) UNSIGNED NOT NULL COMMENT 'vydavatelstvi',
	`isbn` VARCHAR(30) NOT NULL COMMENT 'ISBN vydani',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_publisher`) REFERENCES `publisher` (`id_publisher`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knizni vydani';

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
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`id_user`,`id_book`)
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
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena'
) ENGINE = InnoDB COMMENT = 'typy entit, ke kterym mohou byt vedeny diskuse';

DROP TABLE IF EXISTS discussion;
CREATE TABLE discussion (
	`id_discussion` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_discussable` INT(25) UNSIGNED NOT NULL COMMENT 'typ entity, k niz se diskuse vede',
	`id_discussed` INT(25) UNSIGNED NOT NULL COMMENT 'ID entity, ke ktere se diskuse vede',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev diskuse',
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
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'klicova slova pouzita k oznaceni knih';

DROP TABLE IF EXISTS `tagged`;
CREATE TABLE `tagged` (
	`id_tagged` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_tag` INT(25) UNSIGNED NOT NULL COMMENT 'klicove slovo',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'oznacena kniha',
	`updated` TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_book`) REFERENCES `book` (`id_book`) ON UPDATE CASCADE ON DELETE CASCADE
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