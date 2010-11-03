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

var globalExport = {
	
	view: function()
	{
		$('#Export_action').val('view');
		
		// Send form with ajax
		$('#Export').ajaxSubmit({
			success: function(responseText)
			{
				AjaxResponse.handle(responseText);
				$('div.ui-layout-center').html(responseText);
				init();
			}
		});
	},
	
	save: function(compression)
	{
		$('#Export_action').val('save');
		
		// Compression
		if(compression)
		{
			$('#Export_compression').val(compression);
		}
		else
		{
			$('#Export_compression').val('');
		}
		
		$('#Export').submit();
	},
	
	setup: function()
	{
		// Hide all settings divs and show the first
		$('#exporterSettings>div').hide();
		$('#exporterSettings>div:first').show();
		
		// Setup click handlers for checkboxes
		$('#exporterType>fieldset>input').click(function() {
			$('#exporterSettings>div').hide();
			$('#exporterSettings_' + this.value).show();
		});
	}
	
};