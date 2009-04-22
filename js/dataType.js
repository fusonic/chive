var dataType = {
	
	GROUP: 0,
	SUPPORTS_COLLATION: 1,
	SUPPORTS_INDEX: 2,
	SUPPORTS_UNIQUE: 3,
	SUPPORTS_FULLTEXT: 4,
	SUPPORTS_SIZE: 5,
	SUPPORTS_SCALE: 6,
	SUPPORTS_VALUES: 7,
	SUPPORTS_UNSIGNED: 8,
	SUPPORTS_UNSIGNED_ZEROFILL: 9,
	SUPPORTS_ON_UPDATE_CURRENT_TIMESTAMP: 10,
	SUPPORTS_AUTO_INCREMENT: 11,
	
	types: {"bit":["numeric",false,true,true,false,true,false,false,true,true,false,false],"tinyint":["numeric",false,true,true,false,true,false,false,true,true,false,true],"bool":["numeric",false,true,true,false,false,false,false,true,true,false,false],"smallint":["numeric",false,true,true,false,true,false,false,true,true,false,true],"mediumint":["numeric",false,true,true,false,true,false,false,true,true,false,true],"int":["numeric",false,true,true,false,true,false,false,true,true,false,true],"bigint":["numeric",false,true,true,false,true,false,false,true,true,false,true],"float":["numeric",false,true,true,false,true,true,false,true,true,false,true],"double":["numeric",false,true,true,false,true,true,false,true,true,false,true],"decimal":["numeric",false,true,true,false,true,true,false,true,true,false,true],"char":["string",true,true,true,false,true,false,false,false,false,false,false],"varchar":["string",true,true,true,false,true,false,false,false,false,false,false],"tinytext":["string",true,false,false,true,false,false,false,false,false,false,false],"text":["string",true,false,false,true,false,false,false,false,false,false,false],"mediumtext":["string",true,false,false,true,false,false,false,false,false,false,false],"longtext":["string",true,false,false,true,false,false,false,false,false,false,false],"tinyblob":["string",false,false,false,true,false,false,false,false,false,false,false],"blob":["string",false,false,false,true,false,false,false,false,false,false,false],"mediumblob":["string",false,false,false,true,false,false,false,false,false,false,false],"longblob":["string",false,false,false,true,false,false,false,false,false,false,false],"binary":["string",false,true,true,false,true,false,false,false,false,false,false],"varbinary":["string",false,true,true,false,true,false,false,false,false,false,false],"enum":["string",true,true,true,false,false,false,true,false,false,false,false],"set":["string",true,true,true,false,false,false,true,false,false,false,false],"date":["date",false,true,true,false,false,false,false,false,false,false,false],"datetime":["date",false,true,true,false,false,false,false,false,false,false,false],"timestamp":["date",false,true,true,false,false,false,false,false,false,false,false],"time":["date",false,true,true,false,false,false,false,false,false,false,false],"year":["date",false,true,true,true,false,false,false,false,false,false,false]},
	
	check: function(type, property) {
		return dataType.types[type][property];
	}
	
}