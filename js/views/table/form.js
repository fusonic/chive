var tableForm = {
	
	setup: function(idPrefix)
	{
		var engine = $('#' + idPrefix + 'Table_ENGINE').val();
		
		$('#' + idPrefix + 'Table_optionPackKeys').attr('disabled', !storageEngine.check(engine, storageEngine.SUPPORTS_PACK_KEYS));
		$('#' + idPrefix + 'Table_optionDelayKeyWrite').attr('disabled', !storageEngine.check(engine, storageEngine.SUPPORTS_DELAY_KEY_WRITE));
		$('#' + idPrefix + 'Table_optionChecksum').attr('disabled', !storageEngine.check(engine, storageEngine.SUPPORTS_CHECKSUM));
	},
	
	create: function(idPrefix)
	{
		$('#' + idPrefix + 'Table_ENGINE').change(new Function('tableForm.setup("' + idPrefix + '")'));
		tableForm.setup(idPrefix);
	}
	
};