var schemaGeneral = {
	
	// Drop schema
	dropSchema: function()
	{
		var ulObj = $('#dropSchemaDialog ul');
		
		ulObj.append("<li>"+schema+"</li>")
		
		$('#dropSchemaDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Drop schema
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Do truncate request
			$.post(baseUrl + '/schemata/drop', {
				schema: schema
			}, function() {
				window.location.href = baseUrl;
			});
			
			$(this).dialog('close');
		}; 
		$('#dropSchemaDialog').dialog({
			buttons: buttons		
		});
	}
	
};