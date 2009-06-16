DROP DATABASE IF EXISTS `rowtest`;
CREATE DATABASE `rowtest` COLLATE `utf8_general_ci`;

DROP TABLE IF EXISTS `data`;

CREATE TABLE `rowtest`.`data` (
	`test1` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`test2` MEDIUMINT UNSIGNED NOT NULL DEFAULT 3 ,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` FLOAT( 5, 2 ) NOT NULL ,
   	PRIMARY KEY ( `test1`)
) ENGINE = MYISAM;


INSERT INTO `rowtest`.`data` (`test1`,`test2`,`test3`,`test4`) VALUES ('1','2','Test','3.43');


DROP TABLE IF EXISTS `data2`;

CREATE TABLE `rowtest`.`data2` (
	`test1` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`test2` datetime NULL,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` FLOAT( 5, 2 ) NOT NULL ,
   	PRIMARY KEY ( `test1`,`test2`,`test3`)
) ENGINE = MYISAM;


INSERT INTO `rowtest`.`data2` (`test1`,`test2`,`test3`,`test4`) VALUES ('1','2008-3-5 00:00:00','Test','3.43');

