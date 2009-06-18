DROP DATABASE IF EXISTS `tabletest`;
CREATE DATABASE `tabletest` COLLATE `utf8_general_ci`;


DROP TABLE IF EXISTS `tabletest`.`customer`;

CREATE TABLE `tabletest`.`customer` (
id INT NOT NULL, 
test INT NOT NULL,
PRIMARY KEY (id))
ENGINE=INNODB;


DROP TABLE IF EXISTS `tabletest`.`product_order`;

CREATE TABLE `tabletest`.`product_order` (
no INT NOT NULL AUTO_INCREMENT,
product_category INT NOT NULL,
product_id INT NOT NULL,
customer_id INT NOT NULL,
PRIMARY KEY(no),
INDEX (customer_id),
FOREIGN KEY (customer_id)
REFERENCES customer(id) ON DELETE NO ACTION ON UPDATE CASCADE)  ENGINE=INNODB;


DROP TABLE IF EXISTS `tabletest`.`product`;

CREATE TABLE `tabletest`.`product`(
category INT NOT NULL,
id INT NOT NULL,
fk INT NOT NULL, 
price DECIMAL,
PRIMARY KEY(category, id)) ENGINE=INNODB;


DROP TABLE IF EXISTS `tabletest`.`product2`;

CREATE TABLE `tabletest`.`product2`(
id INT NOT NULL,
fk INT NOT NULL, 
var VARCHAR(10),
price DECIMAL,
PRIMARY KEY(id)) ENGINE=INNODB;

DROP TABLE IF EXISTS `tabletest`.`product3`;

CREATE TABLE `tabletest`.`product3`(
id INT NOT NULL,
fk VARCHAR(10), 
price DECIMAL,
PRIMARY KEY(id)) ENGINE=INNODB;


DROP TABLE IF EXISTS `tabletest`.`product4`;

CREATE TABLE `tabletest`.`product4`(
id INT NOT NULL,
fk INT NOT NULL, 
var VARCHAR(10),
price DECIMAL,
PRIMARY KEY(var)) ENGINE=INNODB;

DROP TABLE IF EXISTS `tabletest`.`product5`;

CREATE TABLE `tabletest`.`product5`(
id INT NULL,
fk INT NULL, 
var VARCHAR(10),
price DECIMAL,
PRIMARY KEY(id)) ENGINE=INNODB;