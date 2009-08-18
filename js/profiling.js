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
 * Profiling
 */

var Profiling = {
	
	toggle: function()
	{
		$.post(baseUrl + '/ajaxSettings/toggle', {
					name: 'profiling'				
		}, function() {
			
			src = $('#profiling_indicator').attr('src');
			
			if(src.indexOf('green') > 0)
				$('#profiling_indicator').attr('src', src.replace(/green/, 'red'));
			else {
				$('#profiling_indicator').attr('src', src.replace(/red/, 'green'));
				
			}
			
		});
	}
	
};