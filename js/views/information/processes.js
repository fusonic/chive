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

/*
 * View functions
 */
var informationProcesses = {
	
	killProcess: function(id)
	{
		$('#processes input[type="checkbox"]').attr('checked', false).change();
		$('#processes input[type="checkbox"][value="' + id + '"]').attr('checked', true).change();
		tableProcesses.killProcesses();
	},
	
	killProcesses: function()
	{
		if($('#processes input[name="processes[]"]:checked').length > 0) 
		{
			$('#killProcessDialog').dialog("open");
		}
	},
	
	setup: function() 
	{
		/*
		 * Setup dialog
		 */
		$('#killProcessDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: {
				'No': function() {
					$(this).dialog('close');
				},
				'Yes': function() {
					
					// Collect ids
					var ids = [];
					$('#processes input[name="processes[]"]:checked').each(function(i,o) {
						ids.push($(this).val());
					});
					
					// Do truncate request
					$.post(baseUrl + '/schemata/processes/kill', {
						ids	: 	JSON.stringify(ids)
					}, AjaxResponse.handle);
					
					$(this).dialog('close');
				}
			}		
		});
	}
	
};