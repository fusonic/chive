$(document).ready(function() {
	
	/*
	 * Setup sortable columns
	 */
	
	$('#columns tbody').sortable({
		handle: 'img.icon_arrow_move',
		update: function(event, ui) {
			
			// Fix even/odd classes
			$('#columns tbody tr:even').addClass('even').removeClass('odd');
			$('#columns tbody tr:odd').addClass('odd').removeClass('even');
			
			// Get column id
			var id = ui.item[0].id.substr(8);
			
			// Get position & command
			var prevs = $('#columns_' + id).prevAll();
			if(prevs.length == 0)
			{
				var command = "FIRST";
			}
			else
			{
				var command = "AFTER " + $('#columns_' + id).prev()[0].id.substr(8); 
			}
			
			// Do AJAX request
			$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/columns/move', {
					command: command,
					column: id
				}
			);
			
		}
	});
	
	/*
	 * Setup dialog
	 */
	
	$('#dropColumnsDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		buttons: {
			'No': function() {
				$(this).dialog('close');
			},
			'Yes': function() {
				
				// Collect ids
				var ids = [];
				$('#columns input[name="columns[]"]:checked').each(function() {
					ids.push($(this).val());
				});
				
				// Do drop request
				$.post(baseUrl + '/columns/drop', {
					'schema': schema,
					'table': table,
					'column[]': ids
				}, function() {
					window.location.reload();
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});


/*
 * Edit column
 */

function editColumn(col)
{
	$('#columns_' + col).appendForm(baseUrl + '/schema/' + schema + '/tables/' + table + '/columns/update?col=' + col);
}


/*
 * Drop column
 */

function dropColumns() 
{
	if($('#columns input[name="columns[]"]:checked').length > 0) 
	{
		$('#dropColumnsDialog').dialog("open");
	}
}

function dropColumn(col)
{
	$('#columns input[type="checkbox"]').attr('checked', false).change();
	$('#columns input[type="checkbox"][value="' + col + '"]').attr('checked', true).change();
	dropColumns();
}
