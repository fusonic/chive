/*
 * Notifications
 */

var Notification = {
	
	// Add column
	add: function(type, header, message, code, options)
	{
		$('<div class="notification" style="display: none;" onmouseover="window.clearTimeout($(this).data(\'timeout\'));">' + 
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
		'</div>').purr(options);
	}
	
};