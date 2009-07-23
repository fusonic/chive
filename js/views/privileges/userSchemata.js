var privilegesUserSchemata = {
	
	user: null,
	host: null,
	
	// Add schema specific privilege
	addSchemaPrivilege: function()
	{
		$('#schemata').appendForm(baseUrl + '/privileges/users/'
			+ encodeURIComponent(privilegesUserSchemata.user) + '/'
			+ encodeURIComponent(privilegesUserSchemata.host) + '/schemaActions/create');
	},
	
	// Edit schema specific privilege
	editSchemaPrivilege: function(schema)
	{
		$('#schemata_' + schema).appendForm(baseUrl + '/privileges/users/'
			+ encodeURIComponent(privilegesUserSchemata.user) + '/'
			+ encodeURIComponent(privilegesUserSchemata.host) + '/schemata/'
			+ encodeURIComponent(schema) + '/update');
	},
	
	// Drop schema privileges
	dropSchemaPrivileges: function()
	{
		if($('#schemata input[name="schemata[]"]:checked').length > 0) 
		{
			$('#dropSchemaPrivilegesDialog').dialog("open");
		}
	},
	dropSchemaPrivilege: function(schema)
	{
		$('#schemata input[type="checkbox"]').attr('checked', false).change();
		$('#schemata input[type="checkbox"][value="' + schema + '"]').attr('checked', true).change();
		privilegesUserSchemata.dropSchemaPrivileges();
	},
	
	// Setup dialogs
	setup: function()
	{
		/*
		 * Drop schema privileges
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = [];
			$('#schemata input[name="schemata[]"]:checked').each(function() {
				ids.push($(this).val());
			});

			// Do drop request
			$.post(baseUrl + '/privileges/users/'
				+ encodeURIComponent(privilegesUserSchemata.user) + '/'
				+ encodeURIComponent(privilegesUserSchemata.host) + '/schemaActions/drop', {
				'schemata[]': ids
			}, AjaxResponse.handle);
			$(this).dialog('close');
		}; 
		$('#dropSchemaPrivilegesDialog').dialog({
			buttons: buttons	
		});
	}
	
};