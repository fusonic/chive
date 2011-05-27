/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

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
		sideBar.activate(2);
		$('#bookmarkList').append('<li id="bookmark_' + _id + '">' +
										'<div class="listIconContainer">' +
											'<a onclick="Bookmark.remove(\'' + _schema + '\', \'' + _id + '\');" href="javascript:void(0);">'+
												'<img alt="' + lang.get('core', 'delete') + '" src="' + iconPath + '/16/delete.png" title="' + lang.get('core', 'delete') + '" class="icon icon16 icon_delete" class="disabled" />' +							
											'</a>' +
											'<a onclick="Bookmark.execute(\'' + _schema + '\', \'' + _id + '\');" href="javascript:void(0);">'+
												'<img alt="' + lang.get('core', 'execute') + '" src="' + iconPath + '/16/execute.png" title="' + lang.get('core', 'execute') + '" class="icon icon16 icon_execute"/>' +							
											'</a>' +
										'</div>' +
										'<a class="icon" href="#bookmark/show/' + _id + '" title="' + _query + '"> ' + 
											'<img src="' + iconPath + '/16/bookmark.png" alt="bookmark" title="' + _query + '" class="icon icon16 icon_bookmark" /> ' +
											'<span>' +_name + '</span>' +
										'</a>' +
									'</li>');
		
		$('#bookmarkList').parent().children('div.noEntries').hide();
									
		$('#bookmark_' + _id).effect('highlight', {}, 2000);
		
	},
	
	remove: function(_schema, _id)
	{
		this.schema = _schema;
		this.id = _id;
		
		// Set text of dialog
	
		$('#deleteBookmarkDialog').html(lang.get('message', 'doYouReallyWantToDeleteBookmark')+'<ul></ul>');
		var ulObj= $('#deleteBookmarkDialog ul');
		ulObj.append('<li>'+$('#bookmark_' + _id + ' a span').html()+'</li>');
		$('#deleteBookmarkDialog').dialog("open");
	},
	
	removeFromList: function(_id) 
	{
		$('#bookmarkList li[id="bookmark_' + _id + '"]').hide().remove();
		
		if($('#bookmarkList>li').length == 0)
		{
			$('#bookmarkList').parent().children('div.noEntries').show();
		}
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
					
					data = response.data;
					
					if(data)
					{
						Bookmark.addToList(data.id, data.schema, data.name, data.query);
						$('#newBookmarkName').get(0).value = "";
					}
						
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