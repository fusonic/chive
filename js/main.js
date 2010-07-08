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

var editing = false;
var globalPost = {};

function init() 
{
	$('table.list').each(function() {
		var tBody = this.tBodies[0];
		var rowCount = tBody.rows.length;
		var currentClass = 'odd';
		for(var i = 0; i < rowCount; i++)
		{
			if(!tBody.rows[i].className.match('noSwitch') || i == 0)
			{
				if(currentClass == 'even')
				{
					currentClass = 'odd';
				}
				else
				{
					currentClass = 'even';
				}
			}
			tBody.rows[i].className += ' ' + currentClass;
		}
	});
	
	// Add checkboxes to respective tables
	try 
	{
		$('table.addCheckboxes').addCheckboxes().removeClass('addCheckboxes');
		$('table.editable').editableTable().removeClass('editable');
	}
	catch(ex)
	{
	}
	
	// Reset favicon
	/*
	$('link[rel="shortcut icon"]').attr('href', baseUrl + '/images/favicon2.ico');
	window.setTimeout(function() { $('link[rel="shortcut icon"]').attr('href', baseUrl + '/images/favicon.ico'); }, 3000);
	*/
	// Unset editing
	editing = false;
}

function navigateTo(_url, _post)
{
	globalPost = _post;
	window.location.href = _url;
	
	return false;
}

$(document).ready(function()
{
	// Load sideBar
	var sideBar = $("#sideBar");
	
	$('body').layout({
		
		// General
		applyDefaultStyles: true,

		// North
		north__size: 40,
		north__resizable: false,
		north__closable: false,
		north__spacing_open: 1,

		// West
		west__size: userSettings.sidebarWidth,
		west__initClosed: userSettings.sidebarState == 'closed',
		west__onresize_end: function () {
			sideBar.accordion('resize');
			if($('.ui-layout-west').width() != userSettings.sidebarWidth)
			{
				// Save
				userSettings.sidebarWidth = $('.ui-layout-west').width(); 
				$.post(baseUrl + '/ajaxSettings/set', {
						name: 'sidebarWidth',
						value: $('.ui-layout-west').width()
					}
				);
			}
			return;
		},
		west__onclose_end: function () {
			sideBar.accordion('resize');
			// Save
			$.post(baseUrl + '/ajaxSettings/set', {
					name: 'sidebarState',
					value: 'closed'
				}
			);
			return;
		},
		west__onopen_end: function () {
			sideBar.accordion('resize');
			// Save
			$.post(baseUrl + '/ajaxSettings/set', {
					name: 'sidebarState',
					value: 'open'
				}
			);
			return;
		}
	});
	

	// ACCORDION - inside the West pane
	sideBar.accordion({
		animated: "slide",
		addClasses: false,
		autoHeight: true,
		collapsible: false,
		fillSpace: true,
		selectedClass: "active"
	});
	
	// Trigger resize event for sidebar accordion - doesn't work in webkit-based browsers
	sideBar.accordion('resize');
	

	// Setup list filters

	$('#schemaList').setupListFilter($('#schemaSearch'));
	$('#tableList').setupListFilter($('#tableSearch'));
	$('#viewList').setupListFilter($('#viewSearch'));
	$('#bookmarkList').setupListFilter($('#bookmarkSearch'));
	

	
	/*
	 * Ajax functions
	 */ 
	

	/*
	 * Change jQuery UI dialog defaults
	 */
	$.ui.dialog.prototype.options.width = 400;
	$.ui.dialog.prototype.options.autoOpen = false;
	$.ui.dialog.prototype.options.modal = true;
	$.ui.dialog.prototype.options.resizable = false;

	
	/*
	 * Misc
	 */
	chive.init();
	
})
.keydown(function(e) 
{
	if(e.keyCode >= 48 
		&& e.keyCode <= 90
		&& !e.altKey && !e.ctrlKey && !e.shiftKey 
		&& (e.target == null || (e.target.tagName != 'INPUT' && e.target.tagName != 'TEXTAREA' && e.target.tagName != 'SELECT')))
	{
		var element = $('#tableSearch:visible, #schemaSearch:visible');
		if(element.length == 1)
		{
			element = element.get(0);
			element.value = '';
			element.focus();
		}
	}
});

String.prototype.trim = function() {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

String.prototype.startsWith = function(str)
{
	return (this.match("^"+str)==str);
}


/*
 * Language
 */
var lang = {
	
	get: function(category, variable, parameters) 
	{
		var package = lang[category];
		if(package && package[variable])
		{
			variable = package[variable];
			if(parameters)
			{
				for(var key in parameters)
				{
					variable = variable.replace(key, parameters[key]);
				}
			}
		}
		return variable;
	}
	
};