/*
 * Notifications
 */

var Notification = {
	
	// Add column
	add: function(type, message, header, code, options)
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
				message + 
				'<a href="javascript:void(0);" style="display: block;" onclick="$(this).next().toggle(1000);" alt="show code">Show code</a>' +
				'<pre style="display: none;">' + (code ? code : '') + '</pre>' +
			'</div>' +
		'</div>' +
		'<div class="notification-bottom"></div>' +
		'</div>').purr(options);
	}
	
};