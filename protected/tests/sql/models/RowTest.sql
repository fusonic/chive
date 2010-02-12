DROP DATABASE IF EXISTS `rowtest`;
CREATE DATABASE `rowtest`;

DROP TABLE IF EXISTS `rowtest`.`data`;
CREATE TABLE `rowtest`.`data` (
	`test1` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`test2` DOUBLE NULL DEFAULT 3 ,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` FLOAT( 5, 2 ) NOT NULL ,
	`test5` DATETIME,
	`test6` SET('a','b','c'),
	`test7` ENUM('1','2','3'),
	`test8` TEXT,
	`test9` BLOB,	
   	PRIMARY KEY ( `test1`)
) ENGINE = MYISAM;

INSERT INTO `rowtest`.`data` (`test1`,`test2`,`test3`,`test4`,`test5`,`test6`,`test7`,`test8`,`test9`)
 VALUES ('1','43534534','Test','332.43','2009-11-15','a','1',' Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa',
 '<recipe name="bread" prep_time="5 mins" cook_time="3 hours">
   <title>Basic bread</title>
   <ingredient amount="8" unit="dL">Flour</ingredient>
   <ingredient amount="10" unit="grams">Yeast</ingredient>
   <ingredient amount="4" unit="dL" state="warm">Water</ingredient>
   <ingredient amount="1" unit="teaspoon">Salt</ingredient>
   <instructions>
     <step>Mix all ingredients together.</step>
     <step>Knead thoroughly.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Knead again.</step>
     <step>Place in a bread baking tin.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Bake in the oven at 180(degrees)C for 30 minutes.</step>
   </instructions>
 </recipe>
 ');

CREATE TABLE IF NOT EXISTS `rowtest`.`data2` (
	`test1` INT UNSIGNED NOT NULL ,
	`test2` DOUBLE NULL,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` FLOAT( 5, 2 ) NOT NULL ,
	`test5` DATETIME,
	`test6` SET('a','b','c'),
	`test7` ENUM('1','2','3'),
	`test8` TEXT,
	`test9` BLOB	
 ) ENGINE = MYISAM;

INSERT INTO `rowtest`.`data2` (`test1`,`test2`,`test3`,`test4`,`test5`,`test6`,`test7`,`test8`,`test9`)
 VALUES ('1','123412','Test','332.43','2009-11-15','a','1',' Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa',
 '<recipe name="bread" prep_time="5 mins" cook_time="3 hours">
   <title>Basic bread</title>
   <ingredient amount="8" unit="dL">Flour</ingredient>
   <ingredient amount="10" unit="grams">Yeast</ingredient>
   <ingredient amount="4" unit="dL" state="warm">Water</ingredient>
   <ingredient amount="1" unit="teaspoon">Salt</ingredient>
   <instructions>
     <step>Mix all ingredients together.</step>
     <step>Knead thoroughly.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Knead again.</step>
     <step>Place in a bread baking tin.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Bake in the oven at 180(degrees)C for 30 minutes.</step>
   </instructions>
 </recipe>
 ');
 
 
INSERT INTO `rowtest`.`data2` (`test1`,`test2`,`test3`,`test4`,`test5`,`test6`,`test7`,`test8`,`test9`)
 VALUES ('1','234324','Test','332.43','2009-11-15','a','1',' Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa',
 '<recipe name="bread" prep_time="5 mins" cook_time="3 hours">
   <title>Basic bread</title>
   <ingredient amount="8" unit="dL">Flour</ingredient>
   <ingredient amount="10" unit="grams">Yeast</ingredient>
   <ingredient amount="4" unit="dL" state="warm">Water</ingredient>
   <ingredient amount="1" unit="teaspoon">Salt</ingredient>
   <instructions>
     <step>Mix all ingredients together.</step>
     <step>Knead thoroughly.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Knead again.</step>
     <step>Place in a bread baking tin.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Bake in the oven at 180(degrees)C for 30 minutes.</step>
   </instructions>
 </recipe>
 ');
 
 CREATE TABLE IF NOT EXISTS `rowtest`.`data3` (
	`test1` INT UNSIGNED NOT NULL ,
	`test2` DOUBLE NULL,
	`test3` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
	`test4` FLOAT( 5, 2 ) NOT NULL ,
	`test5` DATETIME,
	`test6` SET('a','b','c'),
	`test7` ENUM('1','2','3'),
	`test8` TEXT,
	`test9` BLOB,
	 PRIMARY KEY ( `test1`,`test2`)
 ) ENGINE = MYISAM;

INSERT INTO `rowtest`.`data3` (`test1`,`test2`,`test3`,`test4`,`test5`,`test6`,`test7`,`test8`,`test9`)
 VALUES ('1','123412','Test','332.43','2009-11-15','a','1',' Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa',
 '<recipe name="bread" prep_time="5 mins" cook_time="3 hours">
   <title>Basic bread</title>
   <ingredient amount="8" unit="dL">Flour</ingredient>
   <ingredient amount="10" unit="grams">Yeast</ingredient>
   <ingredient amount="4" unit="dL" state="warm">Water</ingredient>
   <ingredient amount="1" unit="teaspoon">Salt</ingredient>
   <instructions>
     <step>Mix all ingredients together.</step>
     <step>Knead thoroughly.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Knead again.</step>
     <step>Place in a bread baking tin.</step>
     <step>Cover with a cloth, and leave for one hour in warm room.</step>
     <step>Bake in the oven at 180(degrees)C for 30 minutes.</step>
   </instructions>
 </recipe>
 ');
 
 

 
 