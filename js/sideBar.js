/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

var sideBar = {
	
	activate: function(index)
	{
		$('#sideBar').accordion('activate', index);
	},
	
	loadSchemata: function(callback)
	{
		var loadingIcon = $('div.sidebarHeader.schemaList img.loading');
		var contentUl = $('#sideBar #schemaList');
		
		// Setup loading icon
		loadingIcon.show();
		
		// Do AJAX request
		$.post(baseUrl + '/schemata', {
			sideBar: true
		}, function(data) {
			
			var template = contentUl.children('li.template');
			var templateHtml = template.html();
			var html = '';
			
			// Remove all existing nodes
			contentUl.empty().append(template);
			
			if(data.length > 0)
			{
				// Append all nodes
				for(var i = 0; i < data.length; i++)
				{
					html += '<li class="nowrap">' + templateHtml
						.replace(/#schemaName#/g, $('<div/>').text(data[i]).html()) + '</li>';
				}
				
				contentUl.append(html);
				$('#sideBar #schemaList').parent().children('div.noEntries').hide();
			}
			else
			{
				$('#sideBar #schemaList').parent().children('div.noEntries').show();
			}
			
			// Callback
			if($.isFunction(callback))
			{
				callback();
			}
			
			// Reload list filter
			contentUl.reloadListFilter();
			
			// Hide loading icon
			loadingIcon.hide();
		});
	},
	
	loadTables: function(schema, callback)
	{
		var loadingIcon = $('div.sidebarHeader.tableList img.loading');
		var contentUl = $('#sideBar #tableList');
		
		// Setup loading icon
		loadingIcon.show();
		
		// Do AJAX request
		$.post(baseUrl + '/schema/' + schema + '/tables', {
			sideBar: true
		}, function(data) {
			
			var template = contentUl.children('li.template');
			var templateHtml = template.html();
			var html = '';
			
			// Remove all existing nodes
			contentUl.empty().append(template);
			
			if(data.length > 0)
			{
				// Append all nodes
				for(var i = 0; i < data.length; i++)
				{
					var newHtml = '<li class="nowrap">' + templateHtml
						.replace(/#tableName#/g, $('<div/>').text(data[i].tableName).html())
						.replace(/#rowCount#/g, data[i].rowCount)
						.replace(/#rowCountText#/g, data[i].rowCountText) + '</li>';
					if(data[i].rowCount == 0)
					{
						newHtml = newHtml.replace('icon icon16 icon_browse', 'icon icon16 icon_browse disabled');
					}
					html += newHtml;
				}
				
				contentUl.append(html);
				$('#sideBar #tableList').parent().children('div.noEntries').hide();
			}
			else
			{
				$('#sideBar #tableList').parent().children('div.noEntries').show();
			}
			
			// Callback
			if($.isFunction(callback))
			{
				callback();
			}
			
			// Reload list filter
			contentUl.reloadListFilter();
			
			// Hide loading icon
			loadingIcon.hide();
		});
	},
	
	loadViews: function(schema, callback)
	{
		var loadingIcon = $('div.sidebarHeader.viewList img.loading');
		var contentUl = $('#sideBar #viewList');
		
		// Setup loading icon
		loadingIcon.show();
		
		// Do AJAX request
		$.post(baseUrl + '/schema/' + schema + '/views', {
			sideBar: true
		}, function(data) {
			
			var template = contentUl.children('li.template');
			var templateHtml = template.html();
			var html = '';
			
			// Remove all existing nodes
			contentUl.empty().append(template);
			
			if(data.length > 0)
			{
				// Append all nodes
				for(var i = 0; i < data.length; i++)
				{
					var newHtml = '<li class="nowrap">' + templateHtml
						.replace(/#viewName#/g, $('<div/>').text(data[i].viewName).html()) + '</li>';
					html += newHtml;
				}
				
				contentUl.append(html);
				$('#sideBar #viewList').parent().children('div.noEntries').hide();
			}
			else
			{
				$('#sideBar #viewList').parent().children('div.noEntries').show();
			}
			
			// Callback
			if($.isFunction(callback))
			{
				callback();
			}
			
			// Reload list filter
			contentUl.reloadListFilter();
			
			// Hide loading icon
			loadingIcon.hide();
		});
	}
	
};