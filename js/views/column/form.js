var columnForm = {
	
	setup: function(idPrefix) 
	{
		
		var type = $('#' + idPrefix + 'Column_dataType').val();
		
		$('#' + idPrefix + 'settingSize')[dataType.check(type, dataType.SUPPORTS_SIZE) ? "show" : "hide" ]();
		$('#' + idPrefix + 'settingScale')[dataType.check(type, dataType.SUPPORTS_SCALE) ? "show" : "hide" ]();
		$('#' + idPrefix + 'settingValues')[dataType.check(type, dataType.SUPPORTS_VALUES) ? "show" : "hide" ]();
		$('#' + idPrefix + 'settingCollation')[dataType.check(type, dataType.SUPPORTS_COLLATION) ? "show" : "hide" ]();
		
		// Attributes
		console.log(dataType.check(type, dataType.SUPPORTS_UNSIGNED));
		$('#' + idPrefix + 'Column_attribute_unsigned').attr('disabled', !dataType.check(type, dataType.SUPPORTS_UNSIGNED));
		$('#' + idPrefix + 'Column_attribute_unsignedzerofill').attr('disabled', !dataType.check(type, dataType.SUPPORTS_UNSIGNED_ZEROFILL));
		
		// Indices
		$('#' + idPrefix + 'createIndex').attr('disabled', !dataType.check(type, dataType.SUPPORTS_INDEX));
		$('#' + idPrefix + 'createIndexUnique').attr('disabled', !dataType.check(type, dataType.SUPPORTS_UNIQUE));
		$('#' + idPrefix + 'createIndexFulltext').attr('disabled', !dataType.check(type, dataType.SUPPORTS_FULLTEXT));
		
		// Auto_increment
		if($('#' + idPrefix + 'createIndexPrimary').length == 1)
		{
			var isPrimary = $('#' + idPrefix + 'createIndexPrimary').attr('checked');
		}
		else
		{
			eval('var isPrimary = isPrimary' + idPrefix);
		}
		$('#' + idPrefix + 'Column_autoIncrement').attr('disabled', !(dataType.check(type, dataType.SUPPORTS_AUTO_INCREMENT) && isPrimary));
		
		$('#' + idPrefix + 'Column_COLUMN_DEFAULT').attr('disabled', $('#' + idPrefix + 'Column_autoIncrement').attr('checked'));
		$('#' + idPrefix + 'Column_isNullable').attr('disabled', $('#' + idPrefix + 'Column_autoIncrement').attr('checked'));
		
		if($('#' + idPrefix + 'Column_isNullable').attr('checked') && !$('#' + idPrefix + 'Column_isNullable').attr('disabled'))
		{
			$('#' + idPrefix + 'settingDefaultNullHint').show();
		}
		else
		{
			$('#' + idPrefix + 'settingDefaultNullHint').hide();
		}
		
	},
	
	create: function(idPrefix)
	{
		$('#' + idPrefix + 'Column_dataType').change(new Function('columnForm.setup("' + idPrefix + '")'));
		$('#' + idPrefix + 'Column_autoIncrement').change(new Function('columnForm.setup("' + idPrefix + '")'));
		$('#' + idPrefix + 'createIndexPrimary').change(new Function('columnForm.setup("' + idPrefix + '")'));
		$('#' + idPrefix + 'Column_isNullable').change(new Function('columnForm.setup("' + idPrefix + '")'));
		columnForm.setup(idPrefix);
	}
	
};