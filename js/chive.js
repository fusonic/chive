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

var chive = {
	
	currentLocation: 	window.location.href,
	
	// Turn loading indicator on by default
	loadingIndicator: 	true,
	
	
	/*
	 * Initialize chive
	 */
	init: function()
	{
		// Initialize location checker
		setInterval(chive.checkLocation, 100);
		
		// Load first page if anchor is set
		if(chive.currentLocation.indexOf('#') > -1)
		{
			chive.refresh();
		}
		
		// Set keyboard shortcuts for Yii pager
		$(document)
		.bind('keydown', {combi: 'right', disableInInput: true }, function() {
			var li = $('ul.yiiPager li.selected').next('li');
			if(li.length > 0)
			{
				location.href = li.children('a').attr('href');
			}
		})
		.bind('keydown', {combi: 'left', disableInInput: true }, function() {
			var li = $('ul.yiiPager li.selected').prev('li');
			if(li.length > 0)
			{
				location.href = li.children('a').attr('href');
			}
		});
	
		// Send keep-alive to server every 5 minutes
		if(!location.href.indexOf('login'))
		{
			setInterval(function() {
				$.post(baseUrl + '/site/keepAlive', function(response) {
					if(response != 'OK') 
					{
						reload();
					}
				});
			}, 300000);
		}
		
		if($('#globalSearch').length)
		{
			$('#globalSearch').autocomplete(baseUrl + '/site/search', {
				width:		400,
				formatItem: function(item, position, total, item2) {
					item = JSON.parse(item2);
					return item.text;
				},
				formatResult: function(item, position, total) {
					item = JSON.parse(item.pop());
					return item.plain;
				}
				}).result(function(event, position, item) {
					item = JSON.parse(item);
					window.location = item.target;
				});
		}
		
		// Initialize loading indicator
		$(document)
			.ajaxStart(function() {
				if(this.loadingIndicator)
				{
					$('#loading').css({'background-image': 'url(' + basePath + '/images/loading4.gif)'}).fadeIn();
				}
			})
			.ajaxStop(function() {
				$('#loading').css({'background-image': 'url(' + basePath + '/images/loading5.gif)'}).fadeOut();
			})
			.ajaxError(function(error, xhr) {
				Notification.add('ajaxerror', lang.get('core', 'ajaxRequestFailed'), lang.get('core', 'ajaxRequestFailedText'), xhr.responseText);
				$('#loading').css({'background-image': 'url(' + basePath + '/images/loading5.gif)'}).fadeOut();
			});

	},

	initAce: function(config)
	{
		var editor = ace.edit(config.id + '_editor');
		var session = editor.getSession();
		var div = $('#' + config.id + '_editor');
		var container = $('#' + config.id + '_container');

		// Set SQL edit mode
		var sqlMode = require("ace/mode/sql").Mode;
		session.setMode(new sqlMode());

		// Set theme and layout
		editor.setTheme("ace/theme/chive");
		editor.setShowPrintMargin(false);
		editor.setFontSize("16px");

		// Value
		var textArea = $('#' + config.id);
		session.setValue(textArea.val());
		session.on('change', function() {
			textArea.val(session.getValue());
		});

		// Set resizing to container width
		var containerWidth = container.width();
		window.setInterval(function() {
			if(container.width() != containerWidth)
			{
				containerWidth = container.width();
				editor.resize();
			}
		}, 100);

		// Set autogrow
		if(config.autogrow)
		{
			var minHeight = config.height;
			var maxHeight = 300;
			session.on('change', function() {
				var lines = session.getValue().split("\n").length;

				var calculatedHeight = lines * 18 + 20;
				if(calculatedHeight > maxHeight)
				{
					calculatedHeight = maxHeight;
				}
				else if(calculatedHeight < minHeight)
				{
					calculatedHeight = minHeight;
				}

				div.height(calculatedHeight);
				editor.resize();
			});
		}
	},

	/*
	 * Loads the specified page.
	 */
	goto: function(location, postValues)
	{
		if(postValues) {
			globalPost = postValues;
		}
		else {
			globalPost = {};
		}
		
		window.location.hash = location;
		chive.currentLocation = window.location.href;
		chive.refresh();
	},
	
	/*
	 * Refreshes the current page using the anchor name.
	 */
	refresh: function()
	{	
		// Build url
		
		var url = chive.currentLocation
			.replace(/\?(.+)#/, '')
			.replace('#', '/')					// Replace # with /
			.replace(/([^:])\/+/g, '$1/');		// Remove multiple slashes

		// Load page into content area
		$.post(url, globalPost, function(response) {
			if(!AjaxResponse.handle(response))
			{
				var content = document.getElementById('content');
				response = '<div style="display: none">Thank\'s to InternetExplorer 8 which requires this dirty hack ...</div>' + response;
				content.innerHTML = response;
				var scripts = content.getElementsByTagName('script');
				for(var i = 0; i < scripts.length; i++)
				{
					$.globalEval(scripts[i].innerHTML);
				}
				init();
			}
			var globalPost = {};
		});
	},
	
	/*
	 * Reloads the whole page.
	 */
	reload: function()
	{
		window.location.reload();
	},
	
	/*
	 * Checks if current location has changed.
	 */
	checkLocation: function()
	{
		if(window.location.href != chive.currentLocation) 
		{
			chive.currentLocation = window.location.href;
			chive.refresh();
		}
	}
	
};