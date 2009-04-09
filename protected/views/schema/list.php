<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/schema/list.js', CClientScript::POS_HEAD); ?>

<div id="dropSchemataDialog" title="<?php echo Yii::t('database', 'dropSchemata'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropSchemata'); ?>
</div>

<h2>Schema List</h2>

<div class="list">
	<div class="pager top">
		<?php $this->widget('CLinkPager',array('pages'=>$pages, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>
	</div>
	<table id="schemata" class="list addCheckboxes">
		<colgroup>
			<col />
			<col style="width: 80px" />
			<col class="collation" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo $sort->link('SCHEMA_NAME'); ?></th>
				<th><?php echo $sort->link('tableCount'); ?></th>
				<th><?php echo $sort->link('DEFAULT_COLLATION_NAME'); ?></th>
				<th colspan="3"></th>
			</tr>
		</thead>
		<tbody>

			<?php foreach($schemaList as $n=>$model): ?>
				<tr id="schemata_<?php echo $model->SCHEMA_NAME; ?>">
					<td>
						<?php echo CHtml::link($model->SCHEMA_NAME, 'schema/' . $model->SCHEMA_NAME, array('rel' => 'no-ajax')); ?>
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
						<a href="#" class="icon">
							<com:Icon name="privileges" size="16" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="editSchema('<?php echo $model->SCHEMA_NAME; ?>')" class="icon">
							<com:Icon name="edit" size="16" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="dropSchema('<?php echo $model->SCHEMA_NAME; ?>')" class="icon">
							<com:Icon name="delete" size="16" />
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5"><?php echo Yii::t('database', 'showingXSchemata', array('{count}' => $schemaCountThisPage, '{total}' => $schemaCount)); ?></th>
				<th>
					<a href="javascript:void(0)" class="icon" onclick="dropSchemata()">
						<com:Icon name="delete" size="16" />
					</a>
				</th>
			</tr>
		</tfoot>
	</table>
	<div style="float: right">
		<a href="javascript:void(0)" onclick="$('#schemata').appendForm(baseUrl + '/schemata/create')">
			Add a new schema (real)
		</a>
	</div>
	<div class="pager bottom">
		<?php $this->widget('CLinkPager',array('pages'=>$pages, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>
	</div>
</div>