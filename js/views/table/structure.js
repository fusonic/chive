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
	
});


/*
 * Edit column
 */

function editColumn(col)
{
	$('#columns_' + col).appendForm(baseUrl + '/schema/' + schema + '/tables/' + table + '/columns/update?col=' + col);
}