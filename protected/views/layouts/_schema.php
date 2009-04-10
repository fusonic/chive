<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/schema/general.js', CClientScript::POS_HEAD); ?>

<script type="text/javascript">
var schema = '<?php echo $this->schema; ?>';
</script>

<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','tables'),
					'icon'=>'table',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schema . '/tables',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('database','sql'),
					'icon'=>'sql',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schema . '/sql',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('action','export'),
					'icon'=>'save',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schema . '/export',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('action','import'),
					'icon'=>'import',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schema . '/import',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('action','operations'),
					'icon'=>'operations',
					'link'=>array(
						'url'=> Yii::app()->baseUrl .  '/schema/' . $this->schema . '/operations',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('action','drop'),
					'icon'=>'drop',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'dropSchema("'.$this->schema.'");'),
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