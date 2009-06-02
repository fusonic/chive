/*
 * View functions
 */
var tableProcesses = {
	
	// Add column
	killProcess: function(id)
	{
		$('#processes input[type="checkbox"]').attr('checked', false).change();
		$('#processes input[type="checkbox"][value="' + id + '"]').attr('checked', true).change();
		tableProcesses.killProcesses();
	},
	
	killProcesses: function()
	{
		if($('#processes input[name="processes[]"]:checked').length > 0) 
		{
			$('#killProcessDialog').dialog("open");
		}
	},
	
	setup: function() 
	{
		/*
		 * Setup dialog
		 */
		$('#killProcessDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() {
					$(this).dialog('close');
				},
				'Yes': function() {
					
					// Collect ids
					var ids = [];
					$('#processes input[name="processes[]"]:checked').each(function(i,o) {
						ids.push($(this).val());
					});
					
					// Do truncate request
					$.post(baseUrl + '/schemata/processes/kill', {
						ids	: 	JSON.stringify(ids)
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});
	}
	
};