DROP DATABASE IF EXISTS `indextest`;
CREATE DATABASE `indextest` DEFAULT COLLATE 'utf8_general_ci';

DROP TABLE IF EXISTS `indextest`.`table1`;
CREATE TABLE IF NOT EXISTS `indextest`.`table1` (
  `pk` int(11) NOT NULL auto_increment,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`pk`),
  UNIQUE KEY `unique` (`datetime`),
  KEY `index` (`varchar`),
  FULLTEXT KEY `fulltext` (`varchar`)
);

DROP TABLE IF EXISTS `indextest`.`table2`;
CREATE TABLE IF NOT EXISTS `indextest`.`table2` (
  `pk` int(11) NOT NULL,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`pk`),
  UNIQUE KEY `unique` (`datetime`),
  KEY `index` (`varchar`),
  FULLTEXT KEY `fulltext` (`varchar`)
);

DROP TABLE IF EXISTS `indextest`.`table3`;
CREATE TABLE IF NOT EXISTS `indextest`.`table3` (
  `pk` int(11) NOT NULL,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`pk`),
  UNIQUE KEY `unique` (`datetime`),
  KEY `index` (`varchar`),
  FULLTEXT KEY `fulltext` (`varchar`)
);

DROP TABLE IF EXISTS `indextest`.`table4`;
CREATE TABLE IF NOT EXISTS `indextest`.`table4` (
  `pk` int(11) NOT NULL,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  UNIQUE KEY `unique` (`datetime`),
  KEY `index` (`varchar`),
  FULLTEXT KEY `fulltext` (`varchar`)
);