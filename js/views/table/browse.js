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
				}, AjaxResponse.handle);
				
				$(this).dialog('close');
			}
		}		
	});
	
	/*
	 * Setup inline editing
	 */
	
	$('.editable td[class!=action]').each(function() 
	{

		eval('var type = tableData.columns.' + $(this).attr('class') + '.dbType');
		
		if(type.indexOf('text') > -1)
		{
			type = 'textarea';
		}
		else
			type = 'text';
			
		$(this).editable(function(value, settings) {
		
			container = this.getContainer();
			
			index = container.parent().attr('id').match(/\d/);
			attribute = container.attr('class');
			
			$.ajax({
				type: 		"POST",
				url: 		baseUrl + '/row/update',
				dataType: 	"html",
				success:	function(response) {
					
					response = JSON.parse(response);
					
					// Update cell content
					container.text(response.data.value);
					
					// Update row data
					eval('rowData[index].'+ response.data.attribute +' = response.data.value;');
					
					AjaxResponse.handle(response);
					
				},
				data: {
					
					data: 		JSON.stringify(rowData[index]),
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
			 onblur    : 'submit',
	         tooltip   : 'Click to edit...',
			 type	   : type,
			 event	   : 'dblclick',
			 data	   : function(value) {
			 	
			 	index = this.getContainer().parent().attr('id').match(/\d/);
				column = this.getContainer().attr('class');
				value = eval('rowData[index].' + column);
				return value;
				
			 }
		
		});
		
	});

});