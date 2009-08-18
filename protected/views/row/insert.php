<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */
<div class="form">
 echo CHtml::form(BASEURL . '/schema/' . $this->schema . '/tables/' . $this->table . '/insert'); ?>

 echo $formBody; ?>

<div class="buttons">
	<input type="hidden" name="insertAndReturn" value="0" id="insertAndReturn" />
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button primary">
		<com:Icon name="add" size="16" text="core.insert" />
		<span> echo Yii::t('core', 'insert'); ?></span>
	</a>
	<a href="javascript:void(0);" onclick="$('#insertAndReturn').attr('value', 1); $('form').submit();" class="icon button">
		<com:Icon name="arrow_return" size="16" text="core.insertAndReturnToThisPage" />
		<span> echo Yii::t('core', 'insertAndReturnToThisPage'); ?></span>
	</a>
</div>

<script type="text/javascript">
	$('form').ajaxForm({
		success: function(responseText) {
			JSON.parse(responseText);
			AjaxResponse.handle(responseText);
		}
	});
</script>

<input type="submit" name="submit" style="display: none;" />

 echo CHtml::endForm(); ?>