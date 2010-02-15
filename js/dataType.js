/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

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