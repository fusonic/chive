var schemaShow = {
	
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
		schemaShow.dropTables();
	},
	dropTables: function()
	{
		if($('#tables input[name="tables[]"]:checked').length > 0) 
		{
			$('#dropTablesDialog').dialog("open");
		}
	},
	
	// Truncate table
	truncateTable: function(table)
	{
		$('#tables input[type="checkbox"]').attr('checked', false).change();
		$('#tables input[type="checkbox"][value="' + table + '"]').attr('checked', true).change();
		schemaShow.truncateTables();
	},
	truncateTables: function()
	{
		if($('#tables input[name="tables[]"]:checked').length > 0) 
		{
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
		$('div.ui-dialog>div[id="dropTablesDialog"]').remove();
		$('#dropTablesDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() 
				{
					$(this).dialog('close');
				},
				'Yes': function() 
				{
					
					// Collect ids
					var ids = schemaShow.getSelectedIds();
					
					// Do drop request
					$.post(baseUrl + '/schema/' + schema + '/tableAction/drop', {
						'tables[]': ids
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});
		
		/*
		 * Setup truncate table dialog
		 */
		$('div.ui-dialog>div[id="truncateTablesDialog"]').remove();
		$('#truncateTablesDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() 
				{
					$(this).dialog('close');
				},
				'Yes': function() 
				{
					
					// Collect ids
					var ids = schemaShow.getSelectedIds();
					
					// Do truncate request
					$.post(baseUrl + '/schema/' + schema + '/tableAction/truncate', {
						'tables[]': ids
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});
	}
	
};