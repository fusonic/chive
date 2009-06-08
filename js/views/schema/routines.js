var schemaRoutines = {
	
	// Drop view
	dropRoutine: function(routine)
	{
		$('#routines input[type="checkbox"]').attr('checked', false).change();
		$('#routines input[type="checkbox"][value="' + routine + '"]').attr('checked', true).change();
		schemaRoutines.dropRoutines();
	},
	dropRoutines: function()
	{
		if($('#routines input[name="routines[]"]:checked').length > 0) 
		{
			$('#dropRoutinesDialog').dialog("open");
		}
	},
	
	// Get selected id's
	getSelectedIds: function()
	{
		var ids = [];
		$('#routines input[name="routines[]"]:checked').each(function() {
			ids.push($(this).val());
		});
		return ids;		
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Setup drop view dialog
		 */
		$('div.ui-dialog>div[id="dropRoutinesDialog"]').remove();
		$('#dropRoutinesDialog').dialog({
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
					var ids = schemaRoutines.getSelectedIds();
					
					// Do drop request
					$.post(baseUrl + '/schema/' + schema + '/routineAction/drop', {
						'routines[]': ids
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});

	}
	
};