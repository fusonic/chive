<div id="dropSchemataDialog" title="<?php echo Yii::t('core', 'dropSchemata'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropSchemata'); ?>
	<ul></ul>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<?php if(Yii::app()->user->privileges->checkGlobal('CREATE')) { ?>
				<a href="javascript:void(0)" onclick="schemaList.addSchema()" class="icon button">
					<?php echo Html::icon('add'); ?>
					<span><?php echo Yii::t('core', 'addSchema'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('add', 16, true); ?>
					<span><?php echo Yii::t('core', 'addSchema'); ?></span>
				</span>
			<?php } ?>
		</div>
	</div>

	<div class="clear"></div>

	<table id="schemata" class="list addCheckboxes selectable">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col style="width: 80px" />
			<col class="collation" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th><?php echo $sort->link('name'); ?></th>
				<th><?php echo $sort->link('tableCount'); ?></th>
				<th colspan="4"><?php echo $sort->link('collation'); ?></th>
			</tr>
		</thead>
		<tbody>

			<?php $canDrop = false; ?>
			<?php foreach($schemaList as $n => $model) { ?>
				<tr id="schemata_<?php echo CHtml::encode($model->SCHEMA_NAME); ?>">
					<td>
						<input type="checkbox" name="schemata[]" value="<?php echo CHtml::encode($model->SCHEMA_NAME); ?>" />
					</td>
					<td>
						<?php echo CHtml::link(CHtml::encode($model->SCHEMA_NAME), Yii::app()->createUrl('schema/' . urlencode($model->SCHEMA_NAME))); ?>
					</td>
					<td class="count">
						<?php echo $model->tableCount; ?>
					</td>
					<td>
						<dfn class="collation" title="<?php echo Collation::getDefinition($model->DEFAULT_COLLATION_NAME); ?>">
							<?php echo $model->DEFAULT_COLLATION_NAME; ?>
						</dfn>
					</td>
					<td>
						<?php echo Html::icon('privileges', 16, true, 'core.privileges'); ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkSchema($model->SCHEMA_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0)" onclick="schemaList.editSchema('<?php echo CHtml::encode($model->SCHEMA_NAME); ?>')" class="icon">
								<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
							</a>
						<?php } else { ?>
							<?php echo Html::icon('edit', 16, true, 'core.edit'); ?>
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkSchema($model->SCHEMA_NAME, 'DROP')) { ?>
							<?php $canDrop = true; ?>
							<a href="javascript:void(0)" onclick="schemaList.dropSchema('<?php echo CHtml::encode($model->SCHEMA_NAME); ?>')" class="icon">
								<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
							</a>
						<?php } else { ?>
							<?php echo Html::icon('delete', 16, true, 'core.drop'); ?>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="6">
					<?php echo Yii::t('core', 'showingXSchemata', array('{count}' => $schemaCountThisPage, '{total}' => $schemaCount)); ?>
				</th>
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
				<a class="icon button" href="javascript:void(0)" onclick="schemaList.dropSchemata()">
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
			<?php if(Yii::app()->user->privileges->checkGlobal('CREATE')) { ?>
				<a href="javascript:void(0)" onclick="schemaList.addSchema()" class="icon button">
					<?php echo Html::icon('add'); ?>
					<span><?php echo Yii::t('core', 'addSchema'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('add', 16, true); ?>
					<span><?php echo Yii::t('core', 'addSchema'); ?></span>
				</span>
			<?php } ?>
		</div>
	</div>

	<div class="clear"></div>

	<?php $this->widget('LinkPager', array('pages' => $pages, 'cssFile' => false, 'nextPageLabel' => '&raquo;', 'prevPageLabel' => '&laquo;')); ?>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaList.setup();
}, 500);
breadCrumb.set([]);
</script>