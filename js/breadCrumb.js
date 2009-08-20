/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

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