<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.form.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/general.js', CClientScript::POS_HEAD); ?>

<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','browse'),
					'icon'=>'browse',
					'link'=>array(
						'url'=> '/database/' . $this->schemaName . '/tables/' . $this->tableName . '/browse',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','structure'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> '/database/' . $this->schemaName . '/tables/' . $this->tableName . '/structure',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','insert'),
					'icon'=>'insert',
					'link'=>array(
						'url'=> '/database/' . $this->schemaName . '/tables/' . $this->tableName . '/insert',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','truncate'),
					'icon'=>'truncate',
					'link'=>array(
						'url'=> '/database/' . $this->schemaName . '/tables/' . $this->tableName . '/truncate',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','drop'),
					'icon'=>'drop',
					'link'=>array(
						'url'=> '/database/' . $this->schemaName . '/tables/' . $this->tableName . '/drop',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
		),
	));
?>

<div id="truncateTableDialog" title="<?php echo Yii::t('database', 'truncateTable'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToTruncateTable', array('{table}'=>$this->tableName)); ?>
</div>
<div id="dropTableDialog" title="<?php echo Yii::t('database', 'dropTable'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropTable', array('{table}'=>$this->tableName)); ?>
</div>

<div id="content">
	<?php echo $content; ?>
</div>