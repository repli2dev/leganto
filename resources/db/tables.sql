-- ZAKLADNI TABULKY PRO PLNENI DATABAZE
-- ------------------------------------

DROP TABLE IF EXISTS language;
CREATE TABLE language (
	id_language INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	name VARCHAR (50) NOT NULL COMMENT 'nazev jazyka',
	locale VARCHAR (10) NOT NULL COMMENT 'zkratka jazyka',
	UNIQUE (name), UNIQUE (locale)
) ENGINE = InnoDB COMMENT = 'Obsahuje pouzivane jazyky';

DROP TABLE IF EXISTS expression;
CREATE TABLE expression (
	id_expression INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_language INT (25) UNSIGNED NULL COMMENT 'jazyk, ve kterem je vyraz napsan - pokud je NULL, pak je jazyk tzv. default',	
	`key` VARCHAR (100) NOT NULL COMMENT 'nazev vyrazu',
	value TEXT NOT NULL COMMENT 'samotny vyraz, ktery se na strankach zobrazi',
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (`key`, id_language)
) ENGINE = InnoDB COMMENT = 'Lokalizovane vyrazy pouzite na strance.';


DROP TABLE IF EXISTS site;
CREATE TABLE site (
	id_domain INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_language INT (25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je domena vedena',
	domain VARCHAR (50) NOT NULL COMMENT 'domena, na ktere web bezi',
	email VARCHAR(100) NOT NULL COMMENT 'e-mail webu',
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE (domain)
) ENGINE = InnoDB COMMENT = 'Jednotlive instance webu.';

-- TABULKY TYKAJICI SE KNIH
-- ------------------------------------

DROP TABLE IF EXISTS serie;
CREATE TABLE serie (
	id_serie INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator'	
) ENGINE = InnoDB COMMENT = 'knizni serie';

DROP TABLE IF EXISTS serie_name;
CREATE TABLE serie_name (
	id_serie_name INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_serie INT(25) UNSIGNED NOT NULL COMMENT 'serie, k niz se nazev vztahuje',
	id_language INT(25) UNSIGNED NOT NULL COMMENT 'jazyk lokalizovaneho nazvu',
	name VARCHAR(255) NOT NULL COMMENT 'lokalizovany nazev serie',
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_serie) REFERENCES serie(id_serie) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'lokalizovane nazvy kniznich serii';

DROP TABLE IF EXISTS book;
CREATE TABLE book (
	id_book INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_serie INT(25) UNSIGNED NULL COMMENT 'serie k niz kniha prislusi (jen pokud k nejake prislusi, jinak NULL)',	
	serie_number INT(25) UNSIGNED NULL COMMENT 'pokud je kniha v nejake serii, oznacuje toto pole poradove cislo knihy v serii',
	FOREIGN KEY (id_serie) REFERENCES serie(id_serie) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knihy nezavisle na lokalizaci';


DROP TABLE IF EXISTS book_title;
CREATE TABLE book_title (
	id_book_title INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_book INT(25) UNSIGNED NOT NULL COMMENT 'kniha, k niz se titul vztahuje',
	id_language INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, ve kterem je kniha napsana',
	title VARCHAR(255) NOT NULL COMMENT 'titul knihy',
	subtitle VARCHAR(255) NULL COMMENT 'podtitul knihy',
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_book) REFERENCES book(id_book) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knizni tituly v jiz lokalizovanych nazvech';

DROP TABLE IF EXISTS writer;
CREATE TABLE writer (
	id_writer INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	type ENUM ('person', 'group') NOT NULL DEFAULT 'person',
	first_name VARCHAR(255) NULL COMMENT 'krestni jmeno autora, vyplneno v pripade, ze type = person',
	last_name VARCHAR(255) NULL COMMENT 'prijmeni autora, vyplneno v pripade, ze type = person',
	group_name VARCHAR(255) NULL COMMENT 'nazev skupiny, vyplneno v pripade, ze type = group'
) ENGINE = InnoDB COMMENT = 'spisovatele - lide, skupiny';

DROP TABLE IF EXISTS written_by;
CREATE TABLE written_by (
	id_written_by INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_book INT(25) UNSIGNED NOT NULL COMMENT 'napsana kniha',
	id_writer INT(25) UNSIGNED NOT NULL COMMENT 'spisovatel, ktery knihu napsal',
	FOREIGN KEY (id_book) REFERENCES book(id_book) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_writer) REFERENCES writer(id_writer) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'vztah mezi spisovateli a knihami';

DROP TABLE IF EXISTS publisher;
CREATE TABLE publisher (
	id_publisher INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	name VARCHAR(255) NOT NULL COMMENT 'nazev nakladatelstvi'
) ENGINE = InnoDB COMMENT = 'vydavatelstvi';

DROP TABLE IF EXISTS edition;
CREATE TABLE edition (
	id_edition INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_book INT(25) UNSIGNED NOT NULL COMMENT 'kniha, jejiz vydani je ulozeno',	
	id_publisher INT(25) UNSIGNED NOT NULL COMMENT 'vydavatelstvi',
	isbn VARCHAR(30) NOT NULL COMMENT 'ISBN vydani',
	FOREIGN KEY (id_book) REFERENCES book(id_book) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_publisher) REFERENCES publisher(id_publisher) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'knizni vydani';

-- TABULKY TYKAJICI SE UZIVATELU
-- ------------------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
	`id_role` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name` VARCHAR(100) NOT NULL UNIQUE
) ENGINE = InnoDB COMMENT = 'role uzivatelu v systemu';

DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
	`id_resource` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	name VARCHAR(100) NOT NULL UNIQUE
) ENGINE = InnoDB COMMENT = 'zdroj, ke kteremu se hlida pristup';

DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
	`id_permission` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_role` INT(25) UNSIGNED NOT NULL COMMENT 'role, ke ktere se pristupova prava vztahuji',
	`id_resource` INT(25) UNSIGNED NOT NULL COMMENT 'zdroj, ke ktere se pristupova prava vztahuji',
	`action` ENUM('read','read_all','edit','edit_all','insert') NULL COMMENT 'typ pristupne akce na danem zdroji, pokud je NULL, jsou na danem zdroji pristupne vsechny akce',
	FOREIGN KEY (id_role) REFERENCES role(id_role) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_resource) REFERENCES resource(id_resource) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE(`id_role`,`id_resource`)
) ENGINE = InnoDB COMMENT = 'pristupova prava roli ke zdrojum';

DROP TABLE IF EXISTS user;
CREATE TABLE user (
	id_user INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_language INT(25) UNSIGNED NOT NULL COMMENT 'preferovany jazyk uzivatele',
	id_role INT(25) UNSIGNED NULL COMMENT 'role, kterou uzivatel zastava v systemu',
	email VARCHAR(255) NOT NULL COMMENT 'e-mail',
	password VARCHAR(255) NOT NULL COMMENT 'hash hesla',
	type ENUM('root','common') NOT NULL DEFAULT 'common',
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_role) REFERENCES role(id_role) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'uzivatele';

DROP TABLE IF EXISTS friendship;
CREATE TABLE friendship (
	id_friendship INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',	
	id_user_from INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery nabidl pratelstvi',
	id_user_to INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu bylo nabidnuto ptatelstvi',
	confirmed ENUM('yes','no') NOT NULL DEFAULT 'no' COMMENT 'informace o tom, zda bylo pratelstvi potvrzeno',
	FOREIGN KEY (id_user_from) REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_user_to) REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'pratelske vztahy mezi uzivateli';

-- TABULKY TYKAJICI SE VZTAHU UZIVATELU A KNIH
-- ------------------------------------

DROP TABLE IF EXISTS shelf;
CREATE TABLE shelf (
	id_shelf INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_user INT(25) UNSIGNED NOT NULL,
	type ENUM('general','read','wanted','owned') NOT NULL DEFAULT 'general' COMMENT 'typ policky - obecna, precteno, poptavano, vlastneno',
	name VARCHAR(255) NULL COMMENT 'nazev policky',
	FOREIGN KEY (id_user) REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'uzivatelovi policky, do kterych si pridava knihy';

DROP TABLE IF EXISTS in_shelf;
CREATE TABLE in_shelf (
	id_in_shelf INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_shelf INT(25) UNSIGNED NOT NULL COMMENT 'police, ve ktere je kniha ulozena',
	id_book INT(25) UNSIGNED NOT NULL COMMENT 'kniha v polici',
	FOREIGN KEY (id_book) REFERENCES book(id_book) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_shelf) REFERENCES shelf(id_shelf) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'ulozene knihy v uzivetelskych policich';

DROP TABLE IF EXISTS opinion;
CREATE TABLE opinion (
	id_opinion INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	id_user INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery nazor napsal',
	id_language INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, kterym je nazor napsany',
	id_book INT(25) UNSIGNED NOT NULL COMMENT 'kniha, ke ktere je nazor napsany',
	rating ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT 'hodnoceni',
	content TEXT NOT NULL COMMENT 'slovni vyjadreni nazoru na knihu',
	FOREIGN KEY (id_user) REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_book) REFERENCES book(id_book) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'hodnocene nazory uzivatelu na knihy';

-- TABULKY TYKAJICI SE DISKUSI
-- ------------------------------------
DROP TABLE IF EXISTS discussable;
CREATE TABLE discussable (
	`id_discussable` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`table` VARCHAR(100) NOT NULL COMMENT 'nazev tabulku, k jejimz enitam mohou uzivatele vest diskuse',
	`name_column` VARCHAR(100) NOT NULL COMMENT 'nazev sloupce, ktery obsahuje nazev entity',
	`identificator_column` VARCHAR(100) NOT NULL COMMENT 'nazev sloupce, ktery obsahuje identifikator entity',
	UNIQUE(`table`)
) ENGINE = InnoDB COMMENT = 'tabulky v databazi, ktere obsahuji diskutovatelne entity';

DROP TABLE IF EXISTS discussed;
CREATE TABLE discussed (
	`id_discussed` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_discussable` INT(25) UNSIGNED NOT NULL COMMENT 'typ entity, k niz se diskuse vede',
	`name` VARCHAR(255) NOT NULL COMMENT 'nazev diskuse',
	FOREIGN KEY (id_discussable) REFERENCES discussable(id_discussable) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'odkazy na diskutovane entity, tvori vlakno diskuse';

DROP TABLE IF EXISTS discussion;
CREATE TABLE discussion (
	`id_discussion` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_discussed` INT(25) UNSIGNED NOT NULL COMMENT 'odkaz na entitu, ke ktere je vedena diskuse',
	`id_user` INT(25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu patri diskusni prispevek',
	`reply` INT(25) UNSIGNED NULL COMMENT 'diskusni prispevek, na ktery tento prispevek reaguje',
	`subject` VARCHAR(255) NULL COMMENT 'predmet diskusniho prispevku',
	`content` TEXT NOT NULL COMMENT 'obsah diskusniho prispevku',
	FOREIGN KEY (id_discussed) REFERENCES discussed(id_discussed) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (reply) REFERENCES discussion(id_discussion) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_user) REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'diskusni prispevky';

-- TABULKY TYKAJICI SE KLICOVYCH SLOV
-- ------------------------------------
DROP TABLE IF EXISTS tag;
CREATE TABLE tag (
	`id_tag` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_language` INT(25) UNSIGNED NOT NULL COMMENT 'jazyk, do nehoz klicove slovo patri',
	`name` VARCHAR(255) NOT NULL COMMENT 'samotne klicove slovo',
	FOREIGN KEY (id_language) REFERENCES language(id_language) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'klicova slova pouzita k oznaceni knih';

DROP TABLE IF EXISTS tagged;
CREATE TABLE tagged (
	`id_tagged` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_tag` INT(25) UNSIGNED NOT NULL COMMENT 'klicove slovo',
	`id_book` INT(25) UNSIGNED NOT NULL COMMENT 'klicove slovo',
	FOREIGN KEY (id_tag) REFERENCES tag(id_tag) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (id_book) REFERENCES book(id_book) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'vztah mezi knihami a klicovymi slovy'
