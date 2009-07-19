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
	`type` ENUM('root','common') NOT NULL DEFAULT 'common' COMMENT 'typ uzivatele; common - kontroluji se pristupova prava, root - nekontroluji ',
	`nick` VARCHAR(255) NOT NULL COMMENT 'prezdivka, pod kterou uzivatel vystupuje',
	`sex` ENUM('male','female') NULL COMMENT 'pohlavi',
	`birth_year` INT(3) NULL COMMENT 'vek',
	`autologin_ticket` VARCHAR(255) NULL COMMENT 'autorizacni retezec pro automaticke prihlaseni',
	`last_logged` DATETIME NULL COMMENT 'cas, kdy byl uzivatel naposled prihlasen',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena',
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
	`updated` TIMESTAMP NULL COMMENT 'cas, kdy byla polozka naposledy zmenena',
	FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'statusy uzivatelu';

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
	`id_message` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`id_user_from` INT (25) UNSIGNED NOT NULL COMMENT 'uzivatel, ktery zpravu poslal',
	`id_user_to` INT (25) UNSIGNED NOT NULL COMMENT 'uzivatel, kteremu byla zprava poslana',
	`from_destroyed` INT(1) DEFAULT 0 COMMENT 'info o tom, zda uzivetel, ktery poslal zpravu, zpravu smazal (0 - ne/1 -ano)',
	`to_destroyed` INT(1) DEFAULT 0 COMMENT 'to same, akorat u prijemce',
	`is_read` INT(1) UNSIGNED DEFAULT 0 COMMENT 'info o tom, zda prijemce precetl zpravu (0 - ne/1 - ano)',
	`subject` VARCHAR(255) NULL COMMENT 'predmet zpravy',
	`content` TEXT NOT NULL COMMENT 'telo zpravy',
	`inserted` DATETIME NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	FOREIGN KEY (`id_user_from`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`id_user_to`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT = 'soukrome zpravy mezi uzivateli';