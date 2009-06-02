/*
 * Profiling
 */

var Profiling = {
	
	toggle: function()
	{
		$.post(baseUrl + '/ajaxSettings/toggle', {
					name: 'profiling'				
		}, function() {
			
			src = $('#profiling_indicator').attr('src');
			
			if(src.indexOf('green') > 0)
				$('#profiling_indicator').attr('src', src.replace(/green/, 'red'));
			else {
				$('#profiling_indicator').attr('src', src.replace(/red/, 'green'));
				
			}
			
		});
	}
	
};