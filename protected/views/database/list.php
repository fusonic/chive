<h2>Databse List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New User',array('create')); ?>]
[<?php echo CHtml::link('Manage User',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>

<table class="list">
	<colgroup>
		<col />
		<col />
		<col />
		<col />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nodecoration"></th>
			<th>Name</th>
			<th>Tables</th>
			<th>Collation</th>
			<th class="nodecoration"></th>
		</tr>
	</thead>
	<tbody>

<% foreach($databaseList as $n=>$model): %>
	<tr>
		<td></td>
		<td>
			<% echo CHtml::link($model->SCHEMA_NAME,array('show','id'=>$model->SCHEMA_NAME)); %>
		</td>
		<td>
			<% echo " " . $model->tableCount . " Tables"; %>
		</td>
		<td>
			<dfn class="collation" title="<% echo $model->collation->definition; %>">
				<% echo $model->collation->COLLATION_NAME; %>
			</dfn>
		</td>
		<td class="action">
			<a href="" class="icon">
				<com:Icon name="edit" size="16" />
			</a>
		</td>
	</tr>
<% endforeach; %>

	</tbody>
	<tfoot>
		<tr>
			<td class="nodecoration"></td>
			<td>10 Databases</td>
			<td class="nodecoration"></td>
			<td class="nodecoration"></td>
			<td class="nodecoration"></td>
		</tr>
	</tfoot>
</table>
<?php foreach($databaseList as $n=>$model): ?>
<div class="item">
<?php echo CHtml::link($model->SCHEMA_NAME,array('show','id'=>$model->SCHEMA_NAME)); ?>
<% echo " " . $model->tableCount . " Tables"; %>
<?php /*
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name')); ?>:
<?php echo CHtml::encode($model->name); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('active')); ?>:
<?php echo CHtml::encode($model->active); ?>
<br/>
*/ ?>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>