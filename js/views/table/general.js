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
				$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/truncate', {
					table: table,
					schema: schema,
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
				$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/drop', {
					table: table,
					schema: schema,
				}, AjaxResponse.handle);
				
				$(this).dialog('close');
			}
		}		
	});
	
});