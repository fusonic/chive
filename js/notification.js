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

var Notification = {
	
	// Add column
	add: function(type, header, message, code, options)
	{
		var icon = type == "ajaxerror" ? "error" : type;
	
		var div = $('<div class="notification" style="display: none">' + 
			'<div class="notification-body">' +
				'<div class="notification-header">' +
					'<span class="icon">' +
						'<img class="icon" src="' + iconPath + '/16/' + icon + '.png" />' +
						'<span>' + header + '</span>' +
					'</span>' +
				'</div>' +
			'<div>' +
				(message ? message : '') + 
				(code ? 
				'<textarea style="display: none" onfocus="this.select()" wrap="off">' + code + '</textarea>' : '') +
			'</div>' +
		'</div>' +
		'<div class="notification-bottom"></div>' +
		(code ? '<a class="code" href="#code" onclick="$(this).parent().find(\'textarea\').toggle(500); return false;" />' : '') +
		'</div>');
		
		
		div.mouseover(function() {
			clearTimeout($(this).data('timeout'));
			clearInterval($(this).data('interval'));
		})
		.mouseout(function() {
			var obj = $(this);
			if(obj.hasClass('not-sticky'))
			{
				var topSpotInt = setInterval( function ()
				{
					// Check to see if our notice is the first non-sticky notice in the list
					if ( obj.prevAll( '.not-sticky' ).length == 0 )
					{ 
						// Stop checking once the condition is met
						clearInterval( topSpotInt );
						clearTimeout(obj.data('timeout'));
						
						// Call the close action after the timeout set in options
						obj.data('timeout', setTimeout( function ()
							{
								if($.isFunction(obj.data('fn.removeNotice')))
								{
									obj.data('fn.removeNotice')();
								}
							}, 2000
						));
					}
				}, 200 );
				obj.data('interval', topSpotInt);
			}
		})
		.purr(options)
		.children('a.sticky')
		.click(function() {
			clearTimeout($(this).data('timeout'));
			clearInterval($(this).data('interval'));
			$(this).removeClass('not-sticky');
		});
		
		if(type == "ajaxerror") 
		{
			div.find("textarea").toggle(500);
		}
	}
};