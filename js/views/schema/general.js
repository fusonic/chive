var schemaGeneral = {
	
	// Drop schema
	dropSchema: function()
	{
		$('#dropSchemaDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		$('#dropSchemaDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() {
					$(this).dialog('close');
				},
				'Yes': function() {
					// Do truncate request
					$.post(baseUrl + '/schemata/drop', {
						schema: schema
					}, function() {
						window.location.href = baseUrl;
					});
					
					$(this).dialog('close');
				}
			}		
		});
	}
	
};