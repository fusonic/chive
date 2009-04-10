var currentLocation = window.location.href;
var mainMenu;
var profiling;

function checkLocation() {

	if(window.location.href != currentLocation) 
	{
		reload();
	}

}

function reload() {
	currentLocation = window.location.href;
	newLocation = currentLocation
		.replace(/\?(.+)#/, '')
		.replace('#', '/')					// Replace # with /
		.replace(/([^:])\/+/g, '$1/');		// Remove multiple slashes
	$('div.ui-layout-center').load(newLocation, {}, init);
	return false;
}

function init() {
	
	$('table.list tbody tr:even').addClass('even');
	$('table.list tbody tr:odd').addClass('odd');
	
	$('div.ui-layout-center form').ajaxForm({
		success: function(responseText, statusText) {
			if(responseText.match(/redirect:(.*)/))
			{
				window.location.href = RegExp.$1;
			}
			else
			{
				$('div.ui-layout-center').html(responseText);
				init();
			}
		}
	});

	if(currentLocation.match(/schema\/(\w+)#tables\/(\w+)\//))
	{
		schema = RegExp.$1.toString();
		table = RegExp.$2.toString();
		
		$('#bc_table a span').text(table);
		$('#bc_table a').attr('href', baseUrl + '/database/' + schema + '#tables/' + table + '/structure');
		$('#bc_table').show();
	}
	else 
	{
		$('bc_table').hide();
	}
	
	// Change ajax links
	var locationWithoutAnchor = new RegExp(location.href.substr(0, location.href.indexOf('#')) + '\/?');
	$('div.ui-layout-center a[rel!="no-ajax"]').each(function() {
		this.href = this.href.replace(locationWithoutAnchor, '#');
	});
	
	// Add checkboxes to respective tables
	try 
	{
		$('table.addCheckboxes').each(function() {
			$(this).addCheckboxes(this.id).removeClass('addCheckboxes');
		});
	}
	catch(exception) {
		alert(exception);
	}
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
			myAccordion.accordion('resize');
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
			myAccordion.accordion('resize');
			// Save
			$.post(baseUrl + '/ajaxSettings/set', {
					name: 'sidebarState',
					value: 'closed'
				}
			);
			return;
		},
		west__onopen_end: function () {
			myAccordion.accordion('resize');
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
	mainMenu = $("#MainMenu").accordion({
		animated: "slide",
		addClasses: false,
		autoHeight: true,
		collapsible: false,
		fillSpace: true,
		selectedClass: "active"
	});
	
	$('#tableList').setupListFilter($('#tableSearch'));
	
	// Mouseover buttons
	$('#MainMenu li').mouseover(function() {
		$(this).children('a.icon10').show();
	});	
	
	$('#MainMenu li').mouseout(function() {
		$(this).children('a.icon10').hide();
	});	
	
	// Ajax loader 
	$(document).ajaxStart(function() {
		$('#loading').css({'background': '#FF0000'}).fadeIn();
	});
	
	$(document).ajaxStop(function() {
		$('#loading').css({'background': '#009900'}).fadeOut();
	});

	setInterval(checkLocation, 100);
	
	if(currentLocation.indexOf('#') > -1)
	{
		reload();
		//$('div.ui-layout-center').load(currentLocation.replace(/#/, '/'), {}, init);
	}
	else
	{
		//init();
	}

});


