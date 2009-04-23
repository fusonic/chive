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
					+ '<img class="icon icon16 icon_arrow_move" title="' + lang.get('core', 'move') + '" alt="' + lang.get('core', 'move') + '" src="/dublin/trunk/images/icons/fugue/16/arrow_move.png"/>'
				+ '</a>'
			+ '</td>'
			+ '<td>'
				+ '<a href="javascript:void(0)" onclick="indexForm.removeColumn(\'' + idPrefix + '\', this)" class="icon">'
					+ '<img class="icon icon16 icon_delete" title="' + lang.get('core', 'remove') + '" alt="' + lang.get('core', 'remove') + '" src="/dublin/trunk/images/icons/fugue/16/delete.png"/>'
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
			$('#' + idPrefix + 'Index_INDEX_NAME').attr('disabled', true);
		}
		else
		{
			$('#' + idPrefix + 'Index_INDEX_NAME').attr('disabled', false);
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
	}
	
};

$(document).ready(function() {
	
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
	
});