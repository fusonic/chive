DROP DATABASE IF EXISTS `triggertest`;
CREATE DATABASE `triggertest` COLLATE `utf8_general_ci`;

DROP TABLE IF EXISTS `triggertest`.`test`;
CREATE TABLE `triggertest`.`test` (
	`test1` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`test2` MEDIUMINT UNSIGNED NOT NULL DEFAULT 3 ,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` ENUM( 'a', 'b' ) NULL ,
	`test5` FLOAT( 5, 2 ) NOT NULL ,
   	PRIMARY KEY ( `test1` , `test2` )
) ENGINE = MYISAM;


DROP TRIGGER IF EXISTS `triggertest`.`trigger1`;

CREATE TRIGGER `triggertest`.`trigger1` BEFORE INSERT ON `triggertest`.`test`
FOR EACH ROW SET @sum = @sum;
