<script type="text/javascript">
var schema = '<?php echo $this->schema; ?>';
</script>

<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','tables'),
					'icon'=>'table',
					'link'=>array(
						'url'=> '#tables',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('database','sql'),
					'icon'=>'sql',
					'link'=>array(
						'url'=> '#sql',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('action','export'),
					'icon'=>'save',
					'link'=>array(
						'url'=> '#export',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('action','import'),
					'icon'=>'import',
					'link'=>array(
						'url'=> '#import',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('action','operations'),
					'icon'=>'operations',
					'link'=>array(
						'url'=> '#operations',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('action','drop'),
					'icon'=>'delete',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'schemaGeneral.dropSchema()'),
					),
					'visible'=>true,
			),
		),
	));
?>

<div id="dropSchemaDialog" title="<?php echo Yii::t('core', 'confirm'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDropDatabase'); ?>
</div>

<?php echo $content; ?>

<script type="text/javascript">
schemaGeneral.setupDialogs();
</script>