var currentLocation = window.location.href;
var sideBar;
var profiling;

/**
* Function : dump()
* Arguments: The data - array,hash(associative array),object
*    The level - OPTIONAL
* Returns  : The textual representation of the array.
* This function was inspired by the print_r function of PHP.
* This will accept some data as the argument and return a
* text that will be a more readable version of the
* array/hash/object that is given.
*/
function dump(arr,level) {
var dumped_text = "";
if(!level) level = 0;

//The padding given at the beginning of the line.
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { //Array/Hashes/Objects
 for(var item in arr) {
  var value = arr[item];
 
  if(typeof(value) == 'object') { //If it is an array,
   dumped_text += level_padding + "'" + item + "' ...\n";
   dumped_text += dump(value,level+1);
  } else {
   dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
  }
 }
} else { //Stings/Chars/Numbers etc.
 dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
} 

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
	
	$('#tableList').setupListFilter($('#tableSearch'));
	
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


