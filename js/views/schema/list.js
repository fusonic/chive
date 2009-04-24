var schemaList = {
	
	// Edit schema
	editSchema: function(db)
	{
		$('#schemata_' + db).appendForm('schemata/update?schema=' + db);
	},

	// Drop schema
	dropSchemata: function() 
	{
		if($('#schemata input[name="schemata[]"]:checked').length > 0) 
		{
			$('#dropSchemataDialog').dialog("open");
		}
	},
	dropSchema: function(db)
	{
		$('#schemata input[type="checkbox"]').attr('checked', false).change();
		$('#schemata input[type="checkbox"][value="' + db + '"]').attr('checked', true).change();
		schemaList.dropSchemata();
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		$('#dropSchemataDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() {
					$(this).dialog('close');
				},
				'Yes': function() {
					
					// Collect ids
					var ids = [];
					$('#schemata input[name="schemata[]"]:checked').each(function() {
						ids.push($(this).val());
					});
					
					// Do drop request
					$.post(baseUrl + '/schemata/drop', {
						'schemata[]': ids
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});
	}
	
}