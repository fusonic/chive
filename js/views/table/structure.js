/*
 * View functions
 */
var tableStructure = {
	
	// Add column
	addColumn: function()
	{
		$('#columns').appendForm(baseUrl + '/schema/' + schema + '/tables/' + table + '/columns/create');
	},
	
	// Edit column
	editColumn: function(col)
	{
		$('#columns_' + col).appendForm(baseUrl + '/schema/' + schema + '/tables/' + table + '/columns/update?col=' + col);
	},
	
	// Drop column
	dropColumn: function(col)
	{
		$('#columns input[type="checkbox"]').attr('checked', false).change();
		$('#columns input[type="checkbox"][value="' + col + '"]').attr('checked', true).change();
		tableStructure.dropColumns();
	},
	dropColumns: function()
	{
		if($('#columns input[name="columns[]"]:checked').length > 0) 
		{
			$('#dropColumnsDialog').dialog("open");
		}
	}
	
};

/*
 * OnLoad
 */
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
	 * Setup sortable indices
	 */
	
	$('#indices ul').sortable({
		update: function(event, ui) {
			var tr = $(this).closest('tr');
			var ul = $(this).closest('ul');
			var indexName = tr.attr('id').substr(8);
			var indexType = tr.children('td:eq(1)').text().trim();
			
			var columns = new Array();
			ul.children('li').each(function() {
				columns.push(this.id.replace(tr.attr('id') + '_', ''));
			});
			
			// Do AJAX requests
			$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/dropIndex', {
					index: indexName,
					type: indexType
				}, function() {
					$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/createIndex', {
						index: indexName,
						type: indexType,
						'columns[]': columns
					});
				}
			);
			
		} 
	}).css('cursor', 'move');
	
	
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
				$.post(baseUrl + '/schema/' + schema + '/tables/' + table + '/columns/drop', {
					'schema': schema,
					'table': table,
					'column[]': ids
				}, function() {
					for(var i = 0; i < ids.length; i++)
					{
						$('#columns_' + ids[i]).remove();
					}
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});