DROP DATABASE IF EXISTS `tabletest`;
CREATE DATABASE `tabletest` COLLATE `utf8_general_ci`;



DROP TABLE IF EXISTS `tabletest`.`tabletest3`;
CREATE TABLE IF NOT EXISTS `tabletest`.`tabletest3` (
  `col1` int(11) NOT NULL,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  KEY `varchar` (`varchar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `tabletest`.`innodb`;
CREATE TABLE IF NOT EXISTS `tabletest`.`innodb` (
  `pk` int(11) NOT NULL auto_increment,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`pk`),
  UNIQUE KEY `datetime` (`datetime`),
  KEY `varchar` (`varchar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `tabletest`.`innodb` (`pk`, `varchar`, `datetime`) VALUES (1, 'a', '2008-01-01');

DROP TABLE IF EXISTS `tabletest`.`myisam`;
CREATE TABLE IF NOT EXISTS `tabletest`.`myisam` (
  `pk` int(11) NOT NULL auto_increment,
  `varchar` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`pk`),
  UNIQUE KEY `datetime` (`datetime`),
  KEY `varchar` (`varchar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


