
DROP DATABASE IF EXISTS `routinetest`;
CREATE DATABASE `routinetest` DEFAULT COLLATE 'utf8_general_ci';

DROP TABLE IF EXISTS `routinetest`.`table1`;
CREATE TABLE IF NOT EXISTS `routinetest`.`table1` (
  `pk` int(11) NOT NULL auto_increment,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`pk`),
  UNIQUE KEY `unique` (`datetime`),
  KEY `index` (`varchar`),
  FULLTEXT KEY `fulltext` (`varchar`)
);

DROP PROCEDURE IF EXISTS `routinetest`.`test_procedure`;

CREATE PROCEDURE `routinetest`.`test_procedure`()
BEGIN

END;


DROP FUNCTION IF EXISTS `routinetest`.`test_function`;

CREATE FUNCTION `routinetest`.`test_function`(value int)
returns int
return value + 1;

