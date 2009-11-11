/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
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

$.datepicker.setDefaults($.datepicker.regional['de']);

function download(_url, _data) 
{
	io = document.createElement('iframe');
	io.src = _url + (_data ? '?' + $.param(_data) : '');
	io.style.display = 'none';
	io = $(io);
	$('body').append(io);
	
	setTimeout(function() {
		io.remove();
	}, 5000);
	
}