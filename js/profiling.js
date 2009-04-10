function toggleProfiling() 
{
	$.post(baseUrl + '/ajaxSettings/set', {
					name: 'profiling',
					value: !status
	}, function() {
		
		reload();
		
	});
}
