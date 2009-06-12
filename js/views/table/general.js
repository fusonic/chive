var tableGeneral = {
	
	// Truncate table
	truncate: function()
	{
		$('#truncateTableDialog').dialog('open');
	},
	
	// Drop table
	drop: function()
	{
		$('#dropTableDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Truncate table
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Do truncate request
			$.post(baseUrl + '/schema/' + schema + '/tableAction/truncate', {
				tables: table,
				schema: schema
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('#truncateTableDialog').dialog({
			buttons: buttons	
		});
		
		/*
		 * Drop table
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Do drop request
			$.post(baseUrl + '/schema/' + schema + '/tableAction/drop', {
				tables: table,
				schema: schema
			}, function(responseText) {
				AjaxResponse.handle(responseText);
				location.href = '#tables';
			});
			
			$(this).dialog('close');
		}; 
		$('#dropTableDialog').dialog({
			buttons: buttons	
		});
	}
	
};