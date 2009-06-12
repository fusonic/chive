var viewGeneral = {
	
	// Drop view
	drop: function()
	{
		$('#dropViewDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Drop view
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		};
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Do drop request
			$.post(baseUrl + '/schema/' + schema + '/viewAction/drop', {
				views: view,
				schema: schema
			}, function(responseText) {
				// @todo(mburtscher): This code loads view structure first and list afterwards. Same when deleting a table ...
				AjaxResponse.handle(responseText);
				location.href = '#views';
			});
			
			$(this).dialog('close');
		};
		$('#dropViewDialog').dialog({
			buttons: buttons	
		});
	}
	
};