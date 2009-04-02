/*
 * Edit database
 */

function editDatabase(db)
{
	$('#databases_' + db).appendForm('databases/update?schema=' + db);
}


/*
 * Drop database
 */

function dropDatabases() 
{
	if($('#databases input[name="databases[]"]:checked').length > 0) 
	{
		$('#dropDatabasesDialog').dialog("open");
	}
}

function dropDatabase(db)
{
	$('#databases input[type="checkbox"]').attr('checked', false).change();
	$('#databases input[type="checkbox"][value="' + db + '"]').attr('checked', true).change();
	dropDatabases();
}


/*
 * Setup dialogs
 */
	
$(document).ready(function() {
	
	$('#dropDatabasesDialog').dialog({
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
				$('#databases input[name="databases[]"]:checked').each(function() {
					ids.push($(this).val());
				});
				
				// Do drop request
				$.post(baseUrl + '/databases/drop', {
					'schema[]': ids
				}, function() {
					window.location.reload();
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});