<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.form.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/general.js', CClientScript::POS_HEAD); ?>

<script type="text/javascript">
var schema = '<?php echo $this->schemaName; ?>';
var table = '<?php echo $this->tableName; ?>';
</script>


<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','browse'),
					'icon'=>'browse',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schemaName . '/tables/' . $this->tableName . '/browse',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','structure'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schemaName . '/tables/' . $this->tableName . '/structure',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','sql'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schemaName . '/tables/' . $this->tableName . '/sql',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','search'),
					'icon'=>'search',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schemaName . '/tables/' . $this->tableName . '/search',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','insert'),
					'icon'=>'insert',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schemaName . '/tables/' . $this->tableName . '/insert',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','truncate'),
					'icon'=>'truncate',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'truncateTable("'.$this->schemaName.'","'.$this->tableName.'");'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','drop'),
					'icon'=>'drop',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'dropTable("'.$this->schemaName.'","'.$this->tableName.'");'),
					),
					'visible'=>true,
			),
		),
	));
?>

<div id="truncateTableDialog" title="<?php echo Yii::t('database', 'truncateTable'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToTruncateTable'); ?>
</div>
<div id="dropTableDialog" title="<?php echo Yii::t('database', 'dropTable'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDropTable'); ?>
</div>

<div id="content">
	<?php echo $content; ?>
</div>