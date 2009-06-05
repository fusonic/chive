var viewGeneral = {
	
	// Drop view
	drop: function()
	{
		$('#dropViewDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		
		$('#dropViewDialog').dialog({
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
					$.post(baseUrl + '/schema/' + schema + '/viewAction/drop', {
						views: view,
						schema: schema
					}, function(responseText) {
						// @todo(mburtscher): This code loads view structure first and list afterwards. Same when deleting a table ...
						AjaxResponse.handle(responseText);
						location.href = '#views';
					});
					
					$(this).dialog('close');
				}
			}		
		});
	}
	
};