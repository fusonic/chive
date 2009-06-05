var schemaViews = {
	
	// Add view
	addView: function()
	{
		$('#views').appendForm(baseUrl + '/schema/' + schema + '/viewAction/create');
	},
	
	// Edit view
	editView: function(view)
	{
		$('#views_' + view).appendForm(baseUrl + '/schema/' + schema + '/views/' + view + '/update');
	},
	
	// Drop view
	dropView: function(view)
	{
		$('#views input[type="checkbox"]').attr('checked', false).change();
		$('#views input[type="checkbox"][value="' + view + '"]').attr('checked', true).change();
		schemaViews.dropViews();
	},
	dropViews: function()
	{
		if($('#views input[name="views[]"]:checked').length > 0) 
		{
			$('#dropViewsDialog').dialog("open");
		}
	},
	
	// Get selected id's
	getSelectedIds: function()
	{
		var ids = [];
		$('#views input[name="views[]"]:checked').each(function() {
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
		$('div.ui-dialog>div[id="dropViewsDialog"]').remove();
		$('#dropViewsDialog').dialog({
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
					var ids = schemaViews.getSelectedIds();
					
					// Do drop request
					$.post(baseUrl + '/schema/' + schema + '/viewAction/drop', {
						'views[]': ids
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});

	}
	
};