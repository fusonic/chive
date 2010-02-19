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

var viewGeneral = {
	
	// Drop view
	drop: function()
	{
		var  ulObj =  $('#dropViewDialog ul');
		
		ulObj.append('<li>' + view + '</li>');
		
		$('#dropViewDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Drop view
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		};
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Do drop request
			$.post(baseUrl + '/schema/' + schema + '/viewAction/drop', {
				views: view,
				schema: schema
			}, function(responseText) {
				// @todo(mburtscher): This code loads view structure first and list afterwards. Same when deleting a table ...
				AjaxResponse.handle(responseText);
				location.href = '#views';
			});
			
			$(this).dialog('close');
		};
		$('#dropViewDialog').dialog({
			buttons: buttons	
		});
	}
	
};