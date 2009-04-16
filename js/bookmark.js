var Bookmark = {
	
	schema: null,
	query: null,
	id: null,
		
	add: function(_schema, _query) 
	{
		this.schema = _schema;
		this.query = _query;
		
		$('#addBookmarkDialog').dialog("open");
	},
	
	addToList: function(_id, _schema, _name, _query) 
	{
		
		sideBar.accordion('activate', 2);
		$('#bookmarkList').append('<li id="bookmark_' + _id + '">' +
										'<a class="icon" href="#bookmark/show/' + _id + '" title="' + _query + '"> ' + 
											'<img src="' + iconPath + '/16/bookmark.png" alt="bookmark" title="' + _query + '" class="icon icon16 icon_bookmark" />' +
											'<span>' +_name + '</span>' +
										'</a>' +
										'<div class="listIconContainer">' +
											'<a onclick="Bookmark.delete(\'' + _schema + '\', \'' + _id + '\');" href="javascript:void(0);">'+
												'<img alt="execute" src="' + iconPath + '/16/delete.png" title="delete" class="icon icon16 icon_delete" class="disabled" />' +							
											'</a>' +
											'<a onclick="Bookmark.execute(\'' + _schema + '\', \'' + _id + '\');" href="javascript:void(0);">'+
												'<img alt="execute" src="' + iconPath + '/16/execute.png" title="execute" class="icon icon16 icon_add"/>' +							
											'</a>' +
									'</li>');
									
		$('#bookmark_1').effect('highlight', {}, 2000);
		
	},
	
	delete: function(_schema, _id)
	{
		this.schema = _schema;
		this.id = _id;
		
		$('#deleteBookmarkDialog').dialog("open");
	},
	
	removeFromList: function(_id) 
	{
		
		$('#bookmarkList li[id="bookmark_' + _id + '"]').hide().remove();
		
	},
	
	execute: function(_schema, _id) 
	{
		
		$.post(baseUrl + '/bookmark/execute',
						
						// Data 
						{
							schema: 	_schema,
							id: 		_id
						}, 
						
						// Callback
						function(response) {
							
							AjaxResponse.handle(response);
							
						});
		
	}
	
};

$(document).ready(function() {
	
	/*
	 * Setup dialogs
	 */
	$('#addBookmarkDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		dialogClass: 'addBookmarkDialog',
		buttons: {
			'Ok': function() {

				$.post(baseUrl + '/bookmark/add', {
							query: 		Bookmark.query,
							schema: 	Bookmark.schema,
							name:		$('#newBookmarkName').get(0).value
				}, function(response) {
					
					response = JSON.parse(response);
					data = response.data;
					
					if(data)
						Bookmark.addToList(data.id, data.schema, data.name, data.query);
						
					AjaxResponse.handle(response);
					
				});
				
				$(this).dialog('close');
				
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}		
	});
	
	$('#deleteBookmarkDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		dialogClass: 'deleteBookmark',
		buttons: {
			'Ok': function() {

				$.post(baseUrl + '/bookmark/delete', {
							schema: 		Bookmark.schema,
							id: 		Bookmark.id
				}, function(response) {
					
					Bookmark.removeFromList(Bookmark.id);
					AjaxResponse.handle(response);
					
				});
				
				$(this).dialog('close');
				
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}		
	});
	
});