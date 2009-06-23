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
	INPUT_TYPE: 12,
	
	types: {"bit":["numeric",false,true,true,false,true,false,false,true,true,false,false,"number"],"tinyint":["numeric",false,true,true,false,true,false,false,true,true,false,true,"number"],"bool":["numeric",false,true,true,false,false,false,false,true,true,false,false,"checkbox"],"smallint":["numeric",false,true,true,false,true,false,false,true,true,false,true,"number"],"mediumint":["numeric",false,true,true,false,true,false,false,true,true,false,true,"number"],"int":["numeric",false,true,true,false,true,false,false,true,true,false,true,"number"],"bigint":["numeric",false,true,true,false,true,false,false,true,true,false,true,"number"],"float":["numeric",false,true,true,false,true,true,false,true,true,false,true,"number"],"double":["numeric",false,true,true,false,true,true,false,true,true,false,true,"number"],"decimal":["numeric",false,true,true,false,true,true,false,true,true,false,true,"number"],"char":["string",true,true,true,true,true,false,false,false,false,false,false,"single"],"varchar":["string",true,true,true,true,true,false,false,false,false,false,false,"single"],"tinytext":["string",true,false,false,true,false,false,false,false,false,false,false,"text"],"text":["string",true,false,false,true,false,false,false,false,false,false,false,"text"],"mediumtext":["string",true,false,false,true,false,false,false,false,false,false,false,"text"],"longtext":["string",true,false,false,true,false,false,false,false,false,false,false,"text"],"tinyblob":["string",false,false,false,true,false,false,false,false,false,false,false,"file"],"blob":["string",false,false,false,true,false,false,false,false,false,false,false,"file"],"mediumblob":["string",false,false,false,true,false,false,false,false,false,false,false,"file"],"longblob":["string",false,false,false,true,false,false,false,false,false,false,false,"file"],"binary":["string",false,true,true,false,true,false,false,false,false,false,false,"single"],"varbinary":["string",false,true,true,false,true,false,false,false,false,false,false,"single"],"enum":["string",true,true,true,false,false,false,true,false,false,false,false,"select"],"set":["string",true,true,true,false,false,false,true,false,false,false,false,"select-multiple"],"date":["date",false,true,true,false,false,false,false,false,false,false,false,"date"],"datetime":["date",false,true,true,false,false,false,false,false,false,false,false,"datetime"],"timestamp":["date",false,true,true,false,false,false,false,false,false,true,false,"single"],"time":["date",false,true,true,false,false,false,false,false,false,false,false,"single"],"year":["date",false,true,true,true,false,false,false,false,false,false,false,"number"]},
	
	check: function(type, property) {
		return dataType.types[type][property];
	}
	
}