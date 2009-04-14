var row;

function deleteRow(_row) 
{
	row = _row;
	$('#deleteRowDialog').dialog('open');
}

function strip_tags(_text){
 return _text.replace(/<\/?[^>]+>/gi, '');
}


/*
 * Setup dialogs
 */
	
$(document).ready(function() {

	$('#deleteRowDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		buttons: {
			'No': function() {
				$(this).dialog('close');
			},
			'Yes': function() {
				
				/*
				headers = new Array();
				$('#browse thead tr th').each(function(i, o) {
					headers[i] = $.trim(strip_tags($(o).html()));
				});
				*/
				data = new Object();

				row.children().each(function(i, o) {
					
					if(i > 2) {
						eval('data.attr_' +i + ' = $.trim($(o).html());');
					}
						
						
				});
				
				// Do truncate request
				$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/row/delete', {
					table: table,
					schema: schema,
					row: data
				}, function() {
					row.hide();
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});