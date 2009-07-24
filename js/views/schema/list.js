var schemaList = {
	
	// Add schema
	addSchema: function()
	{
		$('#schemata').appendForm(baseUrl + '/schemata/create');
	},
	
	// Edit schema
	editSchema: function(db)
	{
		$('#schemata_' + db).appendForm('schemata/update?schema=' + db);
	},

	// Drop schema
	dropSchemata: function() 
	{
		var schemata = schemaList.getSelectedIds();
		if(schemata.length > 0)
		{
			var ulObj = $('#dropSchemataDialog ul');
			ulObj.html('');
			for(var i = 0; i < schemata.length; i++)
			{
				ulObj.append('<li>' + schemata[i] + '</li>');
			}
			$('#dropSchemataDialog').dialog("open");
		}	
	},
	dropSchema: function(db)
	{	
		$('#schemata input[type="checkbox"]').attr('checked', false).change();
		$('#schemata input[type="checkbox"][value="' + db + '"]').attr('checked', true).change();
		schemaList.dropSchemata();
	},
	
		// Get selected id's
	getSelectedIds: function()
	{
			var ids = [];
			$('#schemata input[name="schemata[]"]:checked').each(function() {
				ids.push($(this).val());
			});
		return ids;		
	},
	
	// Setup dialogs
	setup: function()
	{
		/*
		 * Drop schemata
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			
				// Collect ids
			var ids = schemaList.getSelectedIds();
			
			// Do drop request
			$.post(baseUrl + '/schemata/drop', {
				'schemata[]': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('#dropSchemataDialog').dialog({
			buttons: buttons	
		});
	}
	
}