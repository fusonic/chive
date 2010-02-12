/**
 * jQuery Yii ListView plugin file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: jquery.yiilistview.js 99 2010-01-07 20:55:13Z qiang.xue $
 */

;(function($) {
	/**
	 * yiiListView set function.
	 * @param map settings for the list view. Availablel options are as follows:
	 * - ajaxUpdate: array, IDs of the containers whose content may be updated by ajax response
	 * - ajaxVar: string, the name of the GET variable indicating the ID of the element triggering the AJAX request
	 * - pagerClass: string, the CSS class for the pager container
	 * - sorterClass: string, the CSS class for the sorter container
	 * - updateSelector: string, the selector for choosing which elements can trigger ajax requests
	 * - beforeUpdate: function, the function to be called before ajax request is sent
	 * - afterUpdate: function, the function to be called after ajax response is received
	 */
	$.fn.yiiListView = function(settings) {
		var settings = $.extend({}, $.fn.yiiListView.defaults, settings || {});
		return this.each(function(){
			$this = $(this);
			var id = $this.attr('id');
			if(settings.updateSelector == undefined) {
				settings.updateSelector = '#'+id+' .'+settings.pagerClass+' a, #'+id+' .'+settings.sorterClass+' a';
			}
			$.fn.yiiListView.settings[id] = settings;

			if(settings.ajaxUpdate.length > 0) {
				$(settings.updateSelector).live('click',function(){
					$.fn.yiiListView.update(id, {url: $(this).attr('href')});
					return false;
				});
			}
		});
	};

	$.fn.yiiListView.defaults = {
		ajaxUpdate: [],
		ajaxVar: 'ajax',
		pagerClass: 'pager',
		sorterClass: 'sorter'
		// updateSelector: '#id .pager a, '#id .sort a',
		// beforeUpdate: function(id) {},
		// afterUpdate: function(id, data) {},
	};

	$.fn.yiiListView.settings = {};

	/**
	 * Returns the key value for the specified row
	 * @param string the ID of the list view container
	 * @param integer the zero-based index of the data item
	 * @return string the key value
	 */
	$.fn.yiiListView.getKey = function(id, index) {
		return $('#'+id+' > div.keys > span:eq('+index+')').text();
	};

	/**
	 * Returns the URL that generates the list view content.
	 * @param string the ID of the list view container
	 * @return string the URL that generates the list view content.
	 */
	$.fn.yiiListView.getUrl = function(id) {
		return $('#'+id+' > div.keys').attr('title');
	};

	/**
	 * Performs an AJAX-based update of the list view contents.
	 * @param string the ID of the list view container
	 * @param map the AJAX request options (see jQuery.ajax API manual). By default,
	 * the URL to be requested is the one that generates the current content of the list view.
	 */
	$.fn.yiiListView.update = function(id, options) {
		var settings = $.fn.yiiListView.settings[id];
		var data = {};
		data[settings.ajaxVar] = id;
		options = $.extend({
			url: $.fn.yiiListView.getUrl(id),
			data: data,
			success: function(data,status) {
				$.each(settings.ajaxUpdate, function() {
					$('#'+this).html($(data).find('#'+this));
				});
				if(settings.afterUpdate != undefined)
					settings.afterUpdate(id, data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.responseText);
			}
		}, options || {});

		if(settings.beforeUpdate != undefined)
			settings.beforeUpdate(id);
		$.ajax(options);
	};

})(jQuery);