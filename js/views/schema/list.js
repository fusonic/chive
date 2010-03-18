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

var schemaList = {
	
	// Add schema
	addSchema: function()
	{
		$('#schemata').appendForm(baseUrl + '/schemata/create');
	},
	
	// Edit schema
	editSchema: function(db)
	{
		$('#schemata_' + db).appendForm(baseUrl + '/schemata/update?schema=' + db);
	},

	// Drop schema
	dropSchemata: function() 
	{
		var schemata = schemaList.getSelectedIds();
		if(schemata.length > 0)
		{
			var ulObj = $('#dropSchemataDialog ul');
			ulObj.html('');
			for(var i = 0; i < schemata.length; i++)
			{
				ulObj.append('<li>' + schemata[i] + '</li>');
			}
			$('#dropSchemataDialog').dialog("open");
		}	
	},
	dropSchema: function(db)
	{	
		$('#schemata input[type="checkbox"]').attr('checked', false).change();
		$('#schemata input[type="checkbox"][value="' + db + '"]').attr('checked', true).change();
		schemaList.dropSchemata();
	},
	
		// Get selected id's
	getSelectedIds: function()
	{
			var ids = [];
			$('#schemata input[name="schemata[]"]:checked').each(function() {
				ids.push($(this).val());
			});
		return ids;		
	},
	
	// Setup dialogs
	setup: function()
	{
		/*
		 * Drop schemata
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			
				// Collect ids
			var ids = schemaList.getSelectedIds();
			
			// Do drop request
			$.post(baseUrl + '/schemata/drop', {
				'schemata': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('#dropSchemataDialog').dialog({
			buttons: buttons	
		});
	}
	
}