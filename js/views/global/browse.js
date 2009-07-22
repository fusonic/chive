/*
 * View functions
 */
var globalBrowse = {
	
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
			selectedKeyData = new Array();
			
			$('#browse input[name="browse[]"]:checked').each(function() {
				selectedKeyData.push(keyData[this.value.match(/\d+/)]);
			});
			
			navigateTo(baseUrl + '/schema/' + schema + '#tables/' + table + '/row/export', { 
				data	: 	JSON.stringify(selectedKeyData),
				schema	: 	schema,
				table	: 	table
			});
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
	
	insertAsNewRow: function(rowIndex) 
	{
		$.post(baseUrl + '/row/insert', {
			attributes: 	JSON.stringify(keyData[rowIndex]),
			schema: 		schema,
			table:			table
		}, function(responseText) {
			$('div.ui-layout-center').html(responseText);
			init();
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