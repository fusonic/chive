DROP DATABASE IF EXISTS `tabletest`;
CREATE DATABASE `tabletest` COLLATE `utf8_general_ci`;


DROP TABLE IF EXISTS `tabletest`.`customer`;

CREATE TABLE `tabletest`.`customer` (
id INT NOT NULL, 
test INT NOT NULL,
test2 VARCHAR,
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
REFERENCES customer(id)) ENGINE=INNODB;


DROP TABLE IF EXISTS `tabletest`.`product`;

CREATE TABLE `tabletest`.`product`(
category INT NOT NULL,
id INT NOT NULL,
fk INT NOT NULL,
test3 BOOL,
price DECIMAL,
PRIMARY KEY(category, id)) ENGINE=INNODB;
