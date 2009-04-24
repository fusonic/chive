<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/general.js', CClientScript::POS_HEAD); ?>

<script type="text/javascript">
var schema = '<?php echo $this->schema; ?>';
var table = '<?php echo $this->view; ?>';
</script>


<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','browse'),
					'icon'=>'browse',
					'link'=>array(
						'url'=> '#views/' . $this->view . '/browse',
						'htmlOptions'=> array('class'=>'icon'),
					),
			),
			array(	'label'=>Yii::t('database','structure'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> '#views/' . $this->view . '/structure',
						'htmlOptions'=> array('class'=>'icon'),
					),
			),
			array(	'label'=>Yii::t('database','sql'),
					'icon'=>'structure',
					'link'=>array(
						'url'=> '#views/' . $this->view . '/sql',
						'htmlOptions'=> array('class'=>'icon'),
					),
			),
			array(	'label'=>Yii::t('core','search'),
					'icon'=>'search',
					'link'=>array(
						'url'=> '#views/' . $this->view . '/search',
						'htmlOptions'=> array('class'=>'icon'),
					),
			),
			array(	'label'=>Yii::t('database','insert'),
					'icon'=>'insert',
					'link'=>array(
						'url'=> '#views/' . $this->view . '/insert',
						'htmlOptions'=> array('class'=>'icon'),
					),
			),
			array(	'label'=>Yii::t('database','drop'),
					'icon'=>'delete',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'tableGeneral.drop("'.$this->schema.'","'.$this->view.'");'),
					),
			),
		),
	));
?>

<div id="dropTableDialog" title="<?php echo Yii::t('database', 'dropTable'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDropTable'); ?>
</div>

<div id="content">
	<?php echo $content; ?>
</div>