DROP DATABASE IF EXISTS `viewtest`;
CREATE DATABASE `viewtest` COLLATE `utf8_general_ci`;

DROP TABLE IF EXISTS  `viewtest`.`test`;
CREATE TABLE `viewtest`.`test` (
	`test1` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`test2` MEDIUMINT UNSIGNED NOT NULL DEFAULT 3 ,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` ENUM( 'a', 'b' ) NULL ,
	`test5` FLOAT( 5, 2 ) NOT NULL ,
   	PRIMARY KEY ( `test1` , `test2` )
) ENGINE = MYISAM;


DROP VIEW IF EXISTS `viewtest`.`view1`;
CREATE VIEW `viewtest`.`view1` AS SELECT * FROM `viewtest`.`test`;
