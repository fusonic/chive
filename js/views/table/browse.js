/*
 * View functions
 */
var tableBrowse = {
	
	// Add column
	deleteRow: function(row)
	{
		$('#browse input[type="checkbox"]').attr('checked', false).change();
		$('#browse input[type="checkbox"]').eq(row).attr('checked', true).change();
		tableBrowse.deleteRows();
	},
	
	deleteRows: function()
	{
		if($('#browse input[name="browse[]"]:checked').length > 0) 
		{
			$('#deleteRowDialog').dialog("open");
		}
	}
	
};

/*
 * Setup page
 */
	
$(document).ready(function() {

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
						data.push(rowData[i]);
					}
				});
				
				// Do truncate request
				$.post(baseUrl + '/row/delete', {
					data	: 	JSON.stringify(data),
					schema	: 	schema,
					table	: 	table
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
	/*
	 * Setup inline editing
	 */
	
	$('#browse tbody tr td').editable(function(value, settings) {
		
		container = this.getContainer();
		
		index = container.parent().attr('id').match(/\d/);
		attribute = container.attr('class');
		
		$.ajax({
			type: 		"POST",
			url: 		baseUrl + '/row/update',
			dataType: 	"html",
			success:	function(response) {
				
				response = JSON.parse(response);
				
				container.text(response.data.value);
				
				rowData[]
				
				AjaxResponse.handle(response);
				
			},
			data: {
				
				data: 		JSON.stringify(keyData[index]),
				value: 		value,
				attribute:	attribute,
				
				// General information
				table: 		table,
				schema: 	schema
				
			},
			cache:		false
		});
		
	}, {
         indicator : 'Saving...',
		 onblur    : 'ignore',
         tooltip   : 'Click to edit...',
		 event	   : 'dblclick',
		 data	   : function(value) {
		 	
		 	index = this.getContainer().parent().attr('id').match(/\d/);
			column = this.getContainer().attr('class');
			value = eval('rowData[index].' + column);
			return value;
			
		 }
	
	});

});