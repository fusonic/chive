var storageEngine = {
	
	SUPPORTS_DELAY_KEY_WRITE: 0,
	SUPPORTS_CHECKSUM: 1,
	SUPPORTS_PACK_KEYS: 2,
	
	engines: {"MyISAM":[true,true,true],"MEMORY":[false,false,false],"InnoDB":[false,false,false],"BLACKHOLE":[false,false,false],"ARCHIVE":[false,false,false],"CSV":[false,false,false]},
	
	check: function(engine, property) {
		return storageEngine.engines[engine][property];
	}
	
}