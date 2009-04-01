<h2>Database List</h2>

<div class="list">
	<div class="pager top">
		<?php $this->widget('CLinkPager',array('pages'=>$pages, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>
	</div>
	<table id="databaseList" class="list addCheckboxes">
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
				<th>Name</th>
				<th>Tables</th>
				<th>Collation</th>
				<th class="nodecoration" colspan="2"></th>
			</tr>
		</thead>
		<tbody>

			<% foreach($databaseList as $n=>$model): %>
				<tr>
					<td>
						<% echo CHtml::link($model->SCHEMA_NAME,array('show','id'=>$model->SCHEMA_NAME)); %>
					</td>
					<td class="count">
						<% echo $model->tableCount; %>
					</td>
					<td>
						<dfn class="collation" title="<% echo $model->collation->definition; %>">
							<% echo $model->collation->COLLATION_NAME; %>
						</dfn>
					</td>
					<td>
						<a href="#" class="icon">
							<com:Icon name="privileges" size="16" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="$(this).parent().parent().appendForm('databases/update?schema=<%= $model->SCHEMA_NAME %>');" class="icon">
							<com:Icon name="edit" size="16" />
						</a>
					</td>
					<td>
						<a href="#" class="icon">
							<com:Icon name="delete" size="16" />
						</a>
					</td>
				</tr>
			<% endforeach; %>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">10 Databases</th>
				<th>
					<a href="#" class="icon">
						<com:Icon name="delete" size="16" />
					</a>
				</th>
			</tr>
		</tfoot>
	</table>
	<div style="float: right">
		<a href="javascript:void(0)" onclick="$('#databaseList').appendForm('http://localhost/dublin/trunk/databases/create')">Add a new database (real)</a>
	</div>
	<div class="pager bottom">
		<?php $this->widget('CLinkPager',array('pages'=>$pages, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>
	</div>
</div>

<!---
<div class="actionBar">
[<?php echo CHtml::link('New User',array('create')); ?>]
[<?php echo CHtml::link('Manage User',array('admin')); ?>]
</div>
--->