/*
 * Edit database
 */

function editSchema(db)
{
	$('#schemata_' + db).appendForm('schemata/update?schema=' + db);
}


/*
 * Drop database
 */

function dropSchemata() 
{
	if($('#schemata input[name="schemata[]"]:checked').length > 0) 
	{
		$('#dropSchemataDialog').dialog("open");
	}
}

function dropSchema(db)
{
	$('#schemata input[type="checkbox"]').attr('checked', false).change();
	$('#schemata input[type="checkbox"][value="' + db + '"]').attr('checked', true).change();
	dropSchemata();
}


/*
 * Setup dialogs
 */
	
$(document).ready(function() {
	
	$('#dropSchemataDialog').dialog({
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
				$('#schemata input[name="schemata[]"]:checked').each(function() {
					ids.push($(this).val());
				});
				
				// Do drop request
				$.post(baseUrl + '/schemata/drop', {
					'schema[]': ids
				}, function() {
					window.location.reload();
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});