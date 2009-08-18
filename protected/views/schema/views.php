<div id="dropViewsDialog" title="<?php echo Yii::t('database', 'dropViews'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropViews'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaViews.addView()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addView'); ?></span>
			</a>
		</div>
	</div>

	<table class="list addCheckboxes selectable" id="views">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="6"><?php echo $sort->link('TABLE_NAME', Yii::t('database', 'view')); ?></th>
				<th><?php echo $sort->link('IS_UPDATABLE'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if($viewCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						<?php echo Yii::t('database', 'noViews'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($schema->views AS $view) { ?>
				<tr id="views_<?php echo $view->TABLE_NAME; ?>">
					<td>
						<input type="checkbox" name="views[]" value="<?php echo $view->TABLE_NAME; ?>" />
					</td>
					<td>
						<a href="javascript:chive.goto('views/<?php echo $view->TABLE_NAME; ?>/structure')">
							<?php echo $view->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('views/<?php echo $view->TABLE_NAME; ?>/browse')" class="icon">
							<com:Icon name="browse" size="16" text="database.browse" />
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('views/<?php echo $view->TABLE_NAME; ?>/structure')" class="icon">
							<com:Icon name="structure" size="16" text="database.structure" />
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('views/<?php echo $table->TABLE_NAME; ?>/search')" class="icon">
							<com:Icon name="search" size="16" text="core.search" />
						</a>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($view->TABLE_SCHEMA, $view->TABLE_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0);" onclick="schemaViews.editView($(this).closest('tr').attr('id').substr(6))" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						<?php } else { ?>
							<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($view->TABLE_SCHEMA, $view->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaViews.dropView($(this).closest('tr').attr('id').substr(6))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
							<?php $canDrop = true; ?>
						<?php } else { ?>
							<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php echo Yii::t('core', strtolower($view->IS_UPDATABLE)); ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="7"><?php echo Yii::t('database', 'amountViews', array($viewCount, '{amount} '=> $viewCount)); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<?php if($canDrop) { ?>
				<a href="javascript:void(0)" onclick="schemaViews.dropViews()" class="icon button">
					<com:Icon name="delete" size="16" />
					<span><?php echo Yii::t('database', 'drop'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="delete" size="16" disabled="true" />
					<span><?php echo Yii::t('database', 'drop'); ?></span>
				</span>
			<?php } ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaViews.addView()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addView'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaViews.setupDialogs();
}, 500);
</script>