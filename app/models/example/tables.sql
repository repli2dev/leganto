DROP TABLE IF EXISTS `example`;
CREATE TABLE `example` (
	`id_example` INT (25) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'identifikator',
	`name_example` VARCHAR (50) NOT NULL
) ENGINE = InnoDB;