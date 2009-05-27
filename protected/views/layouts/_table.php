<script type="text/javascript">
var schema = '<?php echo $this->schema; ?>';
var table = '<?php echo $this->table; ?>';
</script>

<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','browse'),
					'icon'=>'browse',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/browse',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','structure'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/structure',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','sql'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/sql',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('core','search'),
					'icon'=>'search',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/search',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','insert'),
					'icon'=>'insert',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/insert',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('database','truncate'),
					'icon'=>'truncate',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'tableGeneral.truncate()'),
					),
					'visible'=>Yii::app()->user->privileges->checkTable($this->schema, $this->table, 'DELETE'),
			),
			array(	'label'=>Yii::t('database','drop'),
					'icon'=>'delete',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'tableGeneral.drop()'),
					),
					'visible'=>Yii::app()->user->privileges->checkTable($this->schema, $this->table, 'DROP'),
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

<div>
	<?php echo $content; ?>
</div>

<script type="text/javascript">
tableGeneral.setupDialogs();
</script>