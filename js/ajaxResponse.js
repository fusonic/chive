var AjaxResponse = {
	
	handle: function(data)
	{
		if(!data)
		{
			return;
		}
		
		if(!$.isPlainObject(data))
		{
			return false;
		}
		
		if(data.redirectUrl) 
		{
			window.location.href = data.redirectUrl;
		}
		
		if(data.reload)
		{
			chive.reload();
		}
		
		if(data.refresh) 
		{
			chive.refresh();
		}
		
		if(data.notifications && data.notifications.length > 0) 
		{
			$.each(data.notifications, function() {
				Notification.add(this.type, this.title, this.message, this.code, this.options);
			});
		}
		
		if($.isArray(data.js))
		{
			for(var i = 0; i < data.js.length; i++)
			{
				eval(data.js[i]);
			}
		}
		
		return true;
	}
	
};