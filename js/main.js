var currentLocation = window.location.href;
var sideBar;
var editing = false;

function checkLocation() 
{
	if(window.location.href != currentLocation) 
	{
		refresh();
	}
}

function reload() 
{
	location.reload();
}

function refresh() 
{
	currentLocation = window.location.href;
	newLocation = currentLocation
		.replace(/\?(.+)#/, '')
		.replace('#', '/')					// Replace # with /
		.replace(/([^:])\/+/g, '$1/');		// Remove multiple slashes
	$.post(newLocation, {}, function(response) {
		var content = document.getElementById('content');
		content.innerHTML = response;
		var scripts = content.getElementsByTagName('script');
		for(var i = 0; i < scripts.length; i++)
		{
			$.globalEval(scripts[i].innerHTML);
		}
		init();
		AjaxResponse.handle(response);
	});
	return false;
}

function init() 
{
	$('table.list').each(function() {
		var tBody = this.tBodies[0];
		var rowCount = tBody.rows.length;
		var currentClass = 'odd';
		for(var i = 0; i < rowCount; i++)
		{
			if(!tBody.rows[i].className.match('noSwitch'))
			{
				if(currentClass == 'even')
				{
					currentClass = 'odd';
				}
				else
				{
					currentClass = 'even';
				}
			}
			tBody.rows[i].className += ' ' + currentClass;
		}
	});
	
	$('div.ui-layout-center form').ajaxForm({
		success: function(responseText, statusText) {
			AjaxResponse.handle(responseText);
			$('div.ui-layout-center').html(responseText);
			init();
		}
	});

	// @todo(mburtscher): do this in a more elegant way
	if(currentLocation.match(/schema\/(\w+)#tables\/(\w+)\//))
	{
		schema = RegExp.$1.toString();
		table = RegExp.$2.toString();
		
		$('#bc-table a img').attr('src', iconPath + '/24/table.png');
		$('#bc-table a span').text(table);
		$('#bc-table a').attr('href', baseUrl + '/schema/' + schema + '#tables/' + table + '/structure');
		$('#bc-table').show();
	}
	else if(currentLocation.match(/schema\/(\w+)#views\/(\w+)\//))
	{
		schema = RegExp.$1.toString();
		view = RegExp.$2.toString();
		
		$('#bc-table a img').attr('src', iconPath + '/24/view.png');
		$('#bc-table a span').text(view);
		$('#bc-table a').attr('href', baseUrl + '/schema/' + schema + '#views/' + view + '/structure');
		$('#bc-table').show();
	}
	else 
	{
		$('#bc-table').hide();
	}
	
	// Add checkboxes to respective tables
	try 
	{
		$('table.addCheckboxes').addCheckboxes().removeClass('addCheckboxes');
		$('table.editable').editableTable().removeClass('editable');
	}
	catch(exception) {
		// @todo (rponudic) remove
	}
	
	// Unset editing
	editing = false;
}

$(document).ready(function()
{
	$('body').layout({
		
		// General
		applyDefaultStyles: true,

		// North
		north__size: 40,
		north__resizable: false,
		north__closable: false,
		north__spacing_open: 1,

		// West
		west__size: userSettings.sidebarWidth,
		west__initClosed: userSettings.sidebarState == 'closed',
		west__onresize_end: function () {
			sideBar.accordion('resize');
			if($('.ui-layout-west').width() != userSettings.sidebarWidth)
			{
				// Save
				userSettings.sidebarWidth = $('.ui-layout-west').width(); 
				$.post(baseUrl + '/ajaxSettings/set', {
						name: 'sidebarWidth',
						value: $('.ui-layout-west').width()
					}
				);
			}
			return;
		},
		west__onclose_end: function () {
			sideBar.accordion('resize');
			// Save
			$.post(baseUrl + '/ajaxSettings/set', {
					name: 'sidebarState',
					value: 'closed'
				}
			);
			return;
		},
		west__onopen_end: function () {
			sideBar.accordion('resize');
			// Save
			$.post(baseUrl + '/ajaxSettings/set', {
					name: 'sidebarState',
					value: 'open'
				}
			);
			return;
		}
	});
	

	// ACCORDION - inside the West pane
	sideBar = $("#sideBar").accordion({
		animated: "slide",
		addClasses: false,
		autoHeight: true,
		collapsible: false,
		fillSpace: true,
		selectedClass: "active"
	});
	
	// Trigger resize event for sidebar accordion - doesn't work in webkit-based browsers
	sideBar.accordion('resize');
	
	// Setup list filters

	$('#schemaList').setupListFilter($('#schemaSearch'));
	$('#tableList').setupListFilter($('#tableSearch'));
	$('#bookmarkList').setupListFilter($('#bookmarkSearch'));
	
	
	/*
	 * Ajax functions
	 */ 
	
	// START
	$(document).ajaxStart(function() {
		$('#loading').css({'background-image': 'url(' + baseUrl + '/images/loading4.gif)'}).fadeIn();
		//$('#loading2').show();
	});
	
	// STOP
	$(document).ajaxStop(function() {
		//$('#loading2').hide();
		$('#loading').css({'background-image': 'url(' + baseUrl + '/images/loading5.gif)'}).fadeOut();
	});
	
	// ERROR
	$(document).ajaxError(function() {
		Notification.add('warning', 'Ajax request failed', 'Click <a href="javascript:void(0);" onclick="reload();">here</a> to reload site.', null);
	});

	/*
	 * Change jQuery UI dialog defaults
	 */
	$.ui.dialog.defaults.autoOpen = false;
	$.ui.dialog.defaults.modal = true;
	$.ui.dialog.defaults.resizable = false;


	/*
	 * Misc
	 */
	setInterval(checkLocation, 100);
	
	if(currentLocation.indexOf('#') > -1)
	{
		refresh();
	}
	
	/*
	 * Keepalive packages
	 */
	setInterval(function() {
		$.post(baseUrl + '/site/keepAlive', function(response) {
			if(response != 'OK') {
				reload();
			}
		});
	}, 300000);	//Every 5 minutes
	
})
.keydown(function(e) 
{
	if(e.keyCode >= 48 
		&& e.keyCode <= 90
		&& !e.altKey && !e.ctrlKey && !e.shiftKey 
		&& (e.target == null || (e.target.tagName != 'INPUT' && e.target.tagName != 'TEXTAREA' && e.target.tagName != 'SELECT')))
	{
		var element = $('#tableSearch:visible, #schemaSearch:visible');
		if(element.length == 1)
		{
		element.get(0).focus();
		}
	}
});

var AjaxResponse = {
	
	handle: function(data)
	{
		if(!data)
			return; 
			
		try 
		{
			data = JSON.parse(data);
		}
		catch(Exception) {}
		
		if(data.redirectUrl) 
		{
			window.location.href = data.redirectUrl;
		}
		
		if(data.reload)
		{
			reload();
		}
		
		if(data.refresh) 
		{
			refresh();
		}
		
		if(data.notifications && data.notifications.length > 0) 
		{
			$.each(data.notifications, function() {
				
				Notification.add(this.type, this.title, this.message, this.code, this.options);
				
			});
		}
	}
	
};


String.prototype.trim = function() {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

String.prototype.startsWith = function(str)
{
	return (this.match("^"+str)==str);
}

/*
 * Keyboard shortcuts
 */
$(document).bind('keydown', 'pageup', function() {
	if($('ul.yiiPager li.selected').next('li').length > 0)
	{
		location.href = $('ul.yiiPager li.selected').next('li').find('a').attr('href');
	}
	
});
$(document).bind('keydown', 'pagedown', function() {
	if($('ul.yiiPager li.selected').prev('li').length > 0)
	{
		location.href = $('ul.yiiPager li.selected').prev('li').find('a').attr('href');
	}
	
});
$(document).bind('keydown', 'shift+pagedown', function() {
	if($('ul.yiiPager li.selected').prev('li').length > 0)
	{
		location.href = $('ul.yiiPager li.selected').prev('li').find('a').attr('href');
	}
	
});
/*
 * Language
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