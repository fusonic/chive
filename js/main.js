var currentLocation = window.location.href;

function checkLocation() {

	if(window.location.href != currentLocation) {
		currentLocation = window.location.href;
		$('div.ui-layout-center').load(currentLocation.replace(/#/, '/'), {}, setupListTables);
	}

}

function setupListTables() {
	
	$('table.list tbody tr:even').addClass('even');
	$('table.list tbody tr:odd').addClass('odd');
	
	$('table.addCheckboxes').addCheckboxes().removeClass('addCheckboxes');
	
}

$(document).ready(function()
{

	$('body').layout({
		// General
		applyDefaultStyles: true,

		north__size: 40,
		north__resizable: false,
		north__closable: false,
		north__spacing_open: 1,

		// West
		west__size: userSettings.sidebarWidth,
		west__initClosed: userSettings.sidebarState == 'closed',
		west__onresize_end: function () {
			myAccordion.accordion('resize');
			// Save
			$.post(baseUrl + '/ajaxSettings/set', {
					name: 'sidebarWidth',
					value: $('.ui-layout-west').width()
				}
			);
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
	var myAccordion = $("#MainMenu").accordion({
		selectedClass: "active",
		navigation: true,
		fillSpace: true,
		autoHeight: true,
		collapsible: false,
		animated: "slide"
	});

	setInterval(checkLocation, 100);
	if(currentLocation.indexOf('#') > -1)
	{
		$('div.ui-layout-center').load(currentLocation.replace(/#/, '/'), {}, setupListTables);
	}

});


