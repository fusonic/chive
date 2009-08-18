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
					'visible' => $this->loadView()->getIsUpdatable(),
			),
			array(	'label'=>Yii::t('database','truncate'),
					'icon'=>'truncate',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'tableGeneral.truncate()'),
					),
					'visible'=>Yii::app()->user->privileges->checkTable($this->schema, $this->view, 'DELETE'),
			),
			array(	'label'=>Yii::t('database','drop'),
					'icon'=>'delete',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'viewGeneral.drop("'.$this->schema.'","'.$this->view.'");'),
					),
					'visible'=>Yii::app()->user->privileges->checkTable($this->schema, $this->view, 'DROP'),
			),
		),
	));
?>

<div id="dropViewDialog" title="<?php echo Yii::t('database', 'dropView'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDropView'); ?>
	<ul></ul>
</div>

<div>
	<?php echo $content; ?>
</div>

<script type="text/javascript">
viewGeneral.setupDialogs();
breadCrumb.set([
	{
		icon: 'database',
		href: baseUrl + '/schema/' + schema,
		text: schema
	},
	{
		icon: 'view',
		href: baseUrl + '/schema/' + schema + '#views/' + table + '/structure',
		text: table
	}
]);
sideBar.activate(1);
</script>