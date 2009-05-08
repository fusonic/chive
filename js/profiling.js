/*
 * Profiling
 */

var Profiling = {
	
	toggle: function()
	{
		$.post(baseUrl + '/ajaxSettings/toggle', {
					name: 'profiling'				
		}, function() {
			
			//reload();
			
		});
	}
	
};