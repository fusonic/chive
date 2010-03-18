<div class="tabMenu">
	<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(
				'label' => Yii::t('core','tables'),
				'icon' => 'table',
				'link' => array(
					'url' => 'tables',
					'htmlOptions' => array('class'=>'icon'),
				),
				'visible'=>true,
			),
			array(
				'label' => Yii::t('core','views'),
				'icon' => 'view',
				'link' => array(
					'url' => 'views',
					'htmlOptions' => array('class'=>'icon'),
				),
				'visible' => true,
			),
			array(
				'label' => Yii::t('core','sql'),
				'icon' => 'sql',
				'link' => array(
					'url' => 'sql',
					'htmlOptions' => array('class'=>'icon'),
				),
				'visible' => true,
			),
			array(
				'label' => Yii::t('core','export'),
				'icon' => 'save',
				'link' => array(
					'url' => 'export',
					'htmlOptions' => array('class'=>'icon'),
				),
				'visible' => true,
			),
			array(
				'label' => Yii::t('core','import'),
				'icon' => 'import',
				'link' => array(
					'url' => 'import',
					'htmlOptions' => array('class'=>'icon'),
				),
				'visible' => true,
			),
			array(
				'label' => Yii::t('core', 'routines'),
				'icon' => 'procedure',
				'link' => array(
					'url' => 'routines',
					'htmlOptions' => array('class' => 'icon'),
				),
				'visible' => true,
			),
			array(
				'label' => Yii::t('core','drop'),
				'icon' => 'delete',
				'link' => array(
					'url' => 'javascript:void(0)',
					'htmlOptions' => array('class'=>'icon', 'onclick'=>'schemaGeneral.dropSchema()'),
				),
				'visible' => Yii::app()->user->privileges->checkSchema($this->schema, 'DROP'),
			),
		),
	));
	?>
</div>

<div id="dropSchemaDialog" title="<?php echo Yii::t('core', 'confirm'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropSchema'); ?>
	<ul></ul>
</div>

<div id="content-inner">
	<?php echo $content; ?>
</div>

<script type="text/javascript">
var schema = '<?php echo $this->schema; ?>';
schemaGeneral.setupDialogs();
breadCrumb.set([
	{
		icon: 'database',
		href: 'javascript:chive.goto(\'tables\')',
		text: schema
	}
]);
</script>