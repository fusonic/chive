<div id="dropSchemataDialog" title="<?php echo Yii::t('database', 'dropSchemata'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropSchemata'); ?>
</div>

<h2>Schema List</h2>

<div class="list">

	<div class="buttonContainer"
		
		<div class="left">
			<?php $this->widget('CLinkPager',array('pages'=>$pages, 'cssFile'=>false, 'header'=>'')); ?>
		</div>
		<div class="right">
			<?php if(Yii::app()->user->privileges->checkGlobal('CREATE')) { ?>
				<a href="javascript:void(0)" onclick="schemaList.addSchema()" class="icon button">
					<com:Icon name="add" size="16" />
					<span><?php echo Yii::t('database', 'addSchema'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="add" size="16" disabled="disabled" />
					<span><?php echo Yii::t('database', 'addSchema'); ?></span>
				</span>
			<?php } ?>
		</div>
	</div>
	
	<div class="clear"></div>

	<table id="schemata" class="list addCheckboxes">
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
				<th><?php echo $sort->link('SCHEMA_NAME'); ?></th>
				<th><?php echo $sort->link('tableCount'); ?></th>
				<th colspan="4"><?php echo $sort->link('DEFAULT_COLLATION_NAME'); ?></th>
			</tr>
		</thead>
		<tbody>

			<?php $canDrop = false; ?>
			<?php foreach($schemaList as $n => $model) { ?>
				<tr id="schemata_<?php echo $model->SCHEMA_NAME; ?>">
					<td>
						<input type="checkbox" name="schemata[]" value="<?php echo $model->SCHEMA_NAME; ?>" />
					</td>
					<td>
						<?php echo CHtml::link($model->SCHEMA_NAME, 'schema/' . $model->SCHEMA_NAME); ?>
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
						<com:Icon name="privileges" size="16" text="core.privileges" disabled="true" />
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkSchema($model->SCHEMA_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0)" onclick="schemaList.editSchema('<?php echo $model->SCHEMA_NAME; ?>')" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						<?php } else { ?>
							<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkSchema($model->SCHEMA_NAME, 'DROP')) { ?>
							<?php $canDrop = true; ?>
							<a href="javascript:void(0)" onclick="schemaList.dropSchema('<?php echo $model->SCHEMA_NAME; ?>')" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
						<?php } else { ?>
							<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="6">
					<?php echo Yii::t('database', 'showingXSchemata', array('{count}' => $schemaCountThisPage, '{total}' => $schemaCount)); ?>
				</th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left">
			<div class="withSelected">
				<span class="icon">
					<com:Icon name="arrow_turn_090" size="16" />
					<span><?php echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<?php if($canDrop) { ?>
					<a class="icon button" href="javascript:void(0)" onclick="schemaList.dropSchemata()">
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
		</div>
		<div class="right">
			<?php if(Yii::app()->user->privileges->checkGlobal('CREATE')) { ?>
				<a href="javascript:void(0)" onclick="schemaList.addSchema()" class="icon button">
					<com:Icon name="add" size="16" />
					<span><?php echo Yii::t('database', 'addSchema'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="add" size="16" disabled="disabled" />
					<span><?php echo Yii::t('database', 'addSchema'); ?></span>
				</span>
			<?php } ?>
		</div>
	</div>

	<div class="clear"></div>

	<div class="pager bottom">
		<?php $this->widget('CLinkPager',array('pages'=>$pages, 'cssFile'=>false, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>
	</div>

</div>

<script type="text/javascript">
window.setTimeout(function() {
	schemaList.setupDialogs();
}, 500);
</script>