/*
 * View functions
 */
var login = {
	
	setup: function() 
	{
	
		/*
		 * Setup language dialog
		 */
		$('#languageDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			width: 400
		});
		
		/*
		 * Setup theme dialog
		 */
		$('#themeDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			width: 400
		});		
		
	}
	
};