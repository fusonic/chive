var breadCrumb = {
	
	set: function(data)
	{
		var ul = $('ul.breadCrumb');
		
		// Unset current breadcrumb
		ul.children('li.dynamicCrumb').remove();
		
		// Check if data is array
		if(!$.isArray(data))
		{
			return;
		}
		
		var windowTitle = [];
		
		// Create new breadCrumbs
		for(var i = 0; i < data.length; i++)
		{
			var html = '<a href="' + data[i].href + '"' + (data[i].icon ? ' class="icon"' : '') + '>';
			
			// Add icon
			if(data[i].icon)
			{
				html += '<img src="' + iconPath + '/24/' + data[i].icon + '.png" class="icon icon24 icon_' + data[i].icon + '" width="24" height="24" />';
			}
			
			// Text
			html += '<span>' + data[i].text + '</span>';
			
			html += '</a>';
			
			ul.append('<li class="dynamicCrumb">' + html + '</li>');
			
			windowTitle.push(data[i].text);
		}
		
		// Set window title
		document.title = windowTitle.join(' » ') + ' | Chive';
	}
	
};