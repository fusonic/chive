DROP DATABASE IF EXISTS `rowtest1`;
CREATE DATABASE `schematest1` COLLATE `utf8_general_ci`;

DROP TABLE IF EXISTS `data`;

CREATE TABLE `rowtest`.`data` (
	`id` MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`date` DATETIME NOT NULL ,
	`name` VARCHAR( 50 ) NOT NULL ,
	`blob` BLOB NULL ,
	`description` TEXT NOT NULL
) ENGINE = MYISAM;

INSERT INTO `rowtest`.`data` (`id`, `date`, `name`, `blob`, `description`) VALUES (NULL, '2009-04-14 17:53:41', 'Rene', 0x5361792048656c6c6f20746f204d79204c6974746c6520467269656e64, 'lorel ipsum');