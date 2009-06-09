var informationStorageEngines = {
	
	showDetails: function(storageEngine)
	{
		var obj = $('#' + storageEngine + 'Infos');
		if(obj.is(':visible'))
		{
			obj.find('div.info').slideUp(function() {
				obj.hide();
			});
		}
		else
		{
			obj.show();
			obj.find('div.info').slideDown();
		}
	}
	
};