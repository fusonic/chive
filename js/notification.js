/*
 * Notifications
 */

var Notification = {
	
	// Add column
	add: function(type, header, message, code, options)
	{
		$('<div class="notification" style="display: none;">' + 
			'<div class="notification-body">' +
				'<div class="notification-header">' +
					'<span class="icon">' +
						'<img class="icon" src="' + iconPath + '/16/' + type + '.png" />' +
						'<span>' + header + '</span>' +
					'</span>' +
				'</div>' +
			'<div>' +
				(message ? message : '') + 
				(code ? 
				'<a href="javascript:void(0);" style="display: block;" onclick="$(this).next().toggle(1000);" alt="show code">Show code</a>' +
				'<pre style="display: none;">' + code + '</pre>' : '') +
			'</div>' +
		'</div>' +
		'<div class="notification-bottom"></div>' +
		'</div>')
		.mouseover(function() {
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
	}
	
};