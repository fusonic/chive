DROP DATABASE IF EXISTS `columntest`;
CREATE DATABASE `columntest` COLLATE `utf8_general_ci`;

CREATE TABLE `columntest`.`test` (
	`test1` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`test2` MEDIUMINT UNSIGNED NOT NULL DEFAULT 3 ,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` ENUM( 'a', 'b' ) NULL ,
	`test5` FLOAT( 5, 2 ) NOT NULL ,
   	PRIMARY KEY ( `test1` , `test2` )
) ENGINE = MYISAM;

