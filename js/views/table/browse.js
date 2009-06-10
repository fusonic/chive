/*
 * View functions
 */
var tableBrowse = {
	
	// Add column
	deleteRow: function(row)
	{
		$('#browse input[type="checkbox"]').attr('checked', false).change();
		$('#browse input[type="checkbox"]').eq(row+1).attr('checked', true).change();
		tableBrowse.deleteRows();
	},
	
	deleteRows: function()
	{
		if($('#browse input[name="browse[]"]:checked').length > 0) 
		{
			$('#deleteRowDialog').dialog("open");
		}
	},
	
	exportRows: function() 
	{
		if($('#browse input[name="browse[]"]:checked').length > 0) 
		{
			console.log("implement export");
		}
	},
	
	editRow: function(rowIndex) 
	{
		
		$('#browse tr').eq(rowIndex+1).appendForm(baseUrl + '/row/edit', {
			attributes: 	JSON.stringify(keyData[rowIndex]),
			schema: 		schema,
			table:			table
		});
		
	},
	
	setup: function() 
	{
	
		/*
		 * Setup dialog
		 */
		$('#deleteRowDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() {
					$(this).dialog('close');
				},
				'Yes': function() {
					
					// Collect ids
					var data = [];
					$('#browse input[name="browse[]"]').each(function(i,o) {
						if($(this).attr('checked')) {
							data.push(keyData[i]);
						}
					});
					
					// Do truncate request
					$.post(baseUrl + '/row/delete', {
						data	: 	JSON.stringify(data),
						schema	: 	schema,
						table	: 	table
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});
		
	}
	
};