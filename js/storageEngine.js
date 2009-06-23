var storageEngine = {
	
	SUPPORTS_DELAY_KEY_WRITE: 0,
	SUPPORTS_CHECKSUM: 1,
	SUPPORTS_PACK_KEYS: 2,
	
	engines: {"MyISAM":[true,true,true,false],"MEMORY":[false,false,false,false],"InnoDB":[false,false,false,true],"BerkeleyDB":[false,false,false,false],"BLACKHOLE":[false,false,false,false],"EXAMPLE":[false,false,false,false],"ARCHIVE":[false,false,false,false],"CSV":[false,false,false,false],"ndbcluster":[false,false,false,false],"FEDERATED":[false,false,false,false],"MRG_MYISAM":[false,false,false,false],"ISAM":[false,false,false,false]},
	
	check: function(engine, property) {
		return storageEngine.engines[engine][property];
	}
	
}