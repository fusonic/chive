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

var schemaGeneral = {
	
	// Drop schema
	dropSchema: function()
	{
		var ulObj = $('#dropSchemaDialog ul');
		
		ulObj.find('li').remove();
		ulObj.append("<li>"+schema+"</li>")
		
		$('#dropSchemaDialog').dialog('open');
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Drop schema
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Do truncate request
			$.post(baseUrl + '/schemata/drop', {
				schemata: schema
			}, function() {
				window.location.href = baseUrl;
			});
			
			$(this).dialog('close');
		}; 
		$('#dropSchemaDialog').dialog({
			buttons: buttons		
		});
	}
	
};