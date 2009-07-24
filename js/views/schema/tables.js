var schemaTables = {
	
	// Add table
	addTable: function()
	{
		$('#tables').appendForm(baseUrl + '/schema/' + schema + '/tableAction/create');
	},
	
	// Edit table
	editTable: function(table)
	{
		$('#tables_' + table).appendForm(baseUrl + '/schema/' + schema + '/tables/' + table + '/update');
	},
	
	// Drop table
	dropTable: function(table)
	{
		$('#tables input[type="checkbox"]').attr('checked', false).change();
		$('#tables input[type="checkbox"][value="' + table + '"]').attr('checked', true).change();
		schemaTables.dropTables();
	},
	dropTables: function()
	{
		var tables = schemaTables.getSelectedIds();
		if(tables.length > 0)
		{
			var ulObj = $('#dropTablesDialog ul');
			ulObj.html('');
			for(var i = 0; i < tables.length; i++)
			{
				ulObj.append('<li>' + tables[i] + '</li>');
			}
			$('#dropTablesDialog').dialog("open");
		}
		
	},
	
	// Truncate table
	truncateTable: function(table)
	{
		$('#tables input[type="checkbox"]').attr('checked', false).change();
		$('#tables input[type="checkbox"][value="' + table + '"]').attr('checked', true).change();
		schemaTables.truncateTables();
	},
	truncateTables: function()
	{
		var tables = schemaTables.getSelectedIds();
		if(tables.length > 0)
		{
			var ulObj = $('#truncateTablesDialog ul');
			ulObj.html('');
			for(var i = 0; i < tables.length; i++)
			{
				ulObj.append('<li>' + tables[i] + '</li>');
			}
			$('#truncateTablesDialog').dialog("open");
		}
	},
	
	// Get selected id's
	getSelectedIds: function()
	{
		var ids = [];
		$('#tables input[name="tables[]"]:checked').each(function() {
			ids.push($(this).val());
		});
		return ids;		
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Setup drop table dialog
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = schemaTables.getSelectedIds();
			
			// Do drop request
			$.post(baseUrl + '/schema/' + schema + '/tableAction/drop', {
				'tables[]': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('div.ui-dialog>div[id="dropTablesDialog"]').remove();
		$('#dropTablesDialog').dialog({
			buttons: buttons	
		});
		
		/*
		 * Setup truncate table dialog
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = schemaTables.getSelectedIds();
			
			// Do truncate request
			$.post(baseUrl + '/schema/' + schema + '/tableAction/truncate', {
				'tables[]': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('div.ui-dialog>div[id="truncateTablesDialog"]').remove();
		$('#truncateTablesDialog').dialog({
			buttons: buttons		
		});
	}
	
};