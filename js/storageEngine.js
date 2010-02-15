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

var storageEngine = {
	
	SUPPORTS_DELAY_KEY_WRITE: 0,
	SUPPORTS_CHECKSUM: 1,
	SUPPORTS_PACK_KEYS: 2,
	
	engines: {"MyISAM":[true,true,true,false],"MEMORY":[false,false,false,false],"InnoDB":[false,false,false,true],"BerkeleyDB":[false,false,false,false],"BLACKHOLE":[false,false,false,false],"EXAMPLE":[false,false,false,false],"ARCHIVE":[false,false,false,false],"CSV":[false,false,false,false],"ndbcluster":[false,false,false,false],"FEDERATED":[false,false,false,false],"MRG_MYISAM":[false,false,false,false],"ISAM":[false,false,false,false]},
	
	check: function(engine, property) {
		return storageEngine.engines[engine][property];
	}
	
}