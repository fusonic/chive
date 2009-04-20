var tableGeneral = {
	
	truncate: function()
	{
		$('#truncateTableDialog').dialog('open');
	},
	
	drop: function()
	{
		$('#dropTableDialog').dialog('open');
	}
	
};

/*
 * Setup dialogs
 */
$(document).ready(function() {

	$('#truncateTableDialog').dialog({
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
				// Do truncate request
				$.post(baseUrl + '/schema/' + schema + '/tableAction/truncate', {
					tables: table,
					schema: schema
				}, AjaxResponse.handle);
				
				$(this).dialog('close');
			}
		}		
	});
	
	$('#dropTableDialog').dialog({
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
				// Do drop request
				$.post(baseUrl + '/schema/' + schema + '/tableAction/drop', {
					tables: table,
					schema: schema
				}, function(responseText) {
					AjaxResponse.handle(responseText);
					location.href = '#tables';
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});