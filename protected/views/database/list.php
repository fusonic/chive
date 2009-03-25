<h2>Databse List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New User',array('create')); ?>]
[<?php echo CHtml::link('Manage User',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>

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