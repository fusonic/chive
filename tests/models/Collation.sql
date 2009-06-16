DROP DATABASE IF EXISTS `tabletest`;
CREATE DATABASE `tabletest` COLLATE `utf8_general_ci`;



DROP TABLE IF EXISTS `tabletest`.`tabletest1`;
CREATE TABLE IF NOT EXISTS `tabletest`.`tabletest1` (
  `col1` int(11) NOT NULL,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  KEY `varchar` (`varchar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
