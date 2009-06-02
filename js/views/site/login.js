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
			autoOpen: false
		});
		
		/*
		 * Setup theme dialog
		 */
		$('#themeDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false
		});		
		
	}
	
};