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

var indexForm = {
	
	/*
	 * Add column
	 */
	addColumn: function(idPrefix, col)
	{
		$('#' + idPrefix + 'columns tbody.content').append('<tr>'
			+ '<td>'
				+ '<input type="hidden" name="columns[]" value="' + col + '" />'
				+ col
			+ '</td>'
			+ '<td>'
				+ '<input type="text" name="keyLengths[' + col + ']" class="indexSize"/>'
			+ '</td>'
			+ '<td>'
				+ '<a href="javascript:void(0)" class="icon">'
					+ '<img class="icon icon16 icon_arrow_move" title="' + lang.get('core', 'move') + '" alt="' + lang.get('core', 'move') + '" src="' + iconPath + '/16/arrow_move.png"/>'
				+ '</a>'
			+ '</td>'
			+ '<td>'
				+ '<a href="javascript:void(0)" onclick="indexForm.removeColumn(\'' + idPrefix + '\', this)" class="icon">'
					+ '<img class="icon icon16 icon_delete" title="' + lang.get('core', 'remove') + '" alt="' + lang.get('core', 'remove') + '" src="' + iconPath + '/16/delete.png"/>'
				+ '</a>'
			+ '</td>'
		+ '</tr>');
		indexForm.setup(idPrefix);
	},
	
	/*
	 * Remove column
	 */
	removeColumn: function(idPrefix, obj) 
	{
		$(obj).closest('tr').remove();
		indexForm.setup(idPrefix);
	},
	
	/*
	 * Setup form
	 */
	setup: function(idPrefix)
	{
		// Primary key
		if($('#' + idPrefix + 'Index_type').val() == 'PRIMARY')
		{
			$('#' + idPrefix + 'Index_INDEX_NAME').val('PRIMARY');
			$('#' + idPrefix + 'Index_INDEX_NAME').attr('readonly', true);
		}
		else
		{
			$('#' + idPrefix + 'Index_INDEX_NAME').attr('readonly', false);
		}
		
		// Number of added columns
		if($('#' + idPrefix + 'columns>tbody.content>tr').length > 0)
		{
			$('#' + idPrefix + 'columns>tbody.noItems').hide();
			$('#' + idPrefix + 'columns>tbody.content').show();
		}
		else
		{
			$('#' + idPrefix + 'columns>tbody.noItems').show();
			$('#' + idPrefix + 'columns>tbody.content').hide();
		}
	},
	
	create: function(idPrefix)
	{
		/*
		 * Setup sortable columns
		 */
		$('#' + idPrefix + 'columns tbody.content').sortable({
			handle: 'img.icon_arrow_move'
		});
		
		/*
		 * Setup add column select
		 */
		$('#' + idPrefix + 'addColumn').change(function() {
			
			var obj = $(this);
			var idPrefix = this.id.substr(0, 4);
			
			if(obj.val() == '')
			{
				return;
			}
			
			indexForm.addColumn(idPrefix, obj.val());
			obj.selectOptions('');
			
		});
		
		indexForm.setup(idPrefix);
		
		$('#' + idPrefix + 'Index_type').change(new Function('indexForm.setup("' + idPrefix + '")'));
	}
	
};