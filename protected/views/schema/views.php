<div id="dropViewsDialog" title="<?php echo Yii::t('core', 'dropViews'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropViews'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaViews.addView()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addView'); ?></span>
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
				<th colspan="6"><?php echo $sort->link('name', Yii::t('core', 'view')); ?></th>
				<th><?php echo $sort->link('updatable', Yii::t('core', 'updatable')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if($viewCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						<?php echo Yii::t('core', 'noViews'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php $canDrop = false; ?>
			<?php foreach($schema->views AS $view) { ?>
				<tr id="views_<?php echo $view->TABLE_NAME; ?>">
					<td>
						<input type="checkbox" name="views[]" value="<?php echo $view->TABLE_NAME; ?>" />
					</td>
					<td>
						<?php echo Html::ajaxLink('views/' . $view->TABLE_NAME . '/structure'); ?>
							<?php echo $view->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('views/' . $view->TABLE_NAME . '/browse', array('class' => 'icon')); ?>
							<?php echo Html::icon('browse', 16, false, 'core.browse'); ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('views/' . $view->TABLE_NAME . '/structure', array('class' => 'icon')); ?>
							<?php echo Html::icon('structure', 16, false, 'core.structure'); ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('views/' . $view->TABLE_NAME . '/search', array('class' => 'icon')); ?>
							<?php echo Html::icon('search', 16, false, 'core.search'); ?>
						</a>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($view->TABLE_SCHEMA, $view->TABLE_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0);" onclick="schemaViews.editView($(this).closest('tr').attr('id').substr(6))" class="icon">
								<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
							</a>
						<?php } else { ?>
							<?php echo Html::icon('edit', 16, true, 'core.edit'); ?>
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($view->TABLE_SCHEMA, $view->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaViews.dropView($(this).closest('tr').attr('id').substr(6))" class="icon">
								<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
							</a>
							<?php $canDrop = true; ?>
						<?php } else { ?>
							<?php echo Html::icon('delete', 16, true, 'core.drop'); ?>
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
				<th colspan="7"><?php echo Yii::t('core', 'amountViews', array($viewCount, '{amount} '=> $viewCount)); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<?php echo Html::icon('arrow_turn_090'); ?>
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<?php if($canDrop) { ?>
				<a href="javascript:void(0)" onclick="schemaViews.dropViews()" class="icon button">
					<?php echo Html::icon('delete'); ?>
					<span><?php echo Yii::t('core', 'drop'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('delete', 16, true); ?>
					<span><?php echo Yii::t('core', 'drop'); ?></span>
				</span>
			<?php } ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaViews.addView()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addView'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaViews.setupDialogs();
}, 500);
</script>