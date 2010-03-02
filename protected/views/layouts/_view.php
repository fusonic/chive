<div class="tabMenu">
	<?php $this->widget('TabMenu', array(
		'items'=>array(
			array(
				'label' => Yii::t('core','browse'),
				'icon' => 'browse',
				'link' => array(
					'url' => 'views/' . $this->view . '/browse',
					'htmlOptions' => array('class'=>'icon'),
				),
			),
			array(
				'label' => Yii::t('core','structure'),
				'icon' => 'structure',
				'link' => array(
					'url' => 'views/' . $this->view . '/structure',
					'htmlOptions' => array('class'=>'icon'),
				),
			),
			array(
				'label' => Yii::t('core','sql'),
				'icon' => 'structure',
				'link' => array(
					'url' => 'views/' . $this->view . '/sql',
					'htmlOptions' => array('class'=>'icon'),
				),
			),
			array(
				'label' => Yii::t('core','search'),
				'icon' => 'search',
				'link' => array(
					'url' => 'views/' . $this->view . '/search',
					'htmlOptions' => array('class'=>'icon'),
				),
			),
			array(
				'label' => Yii::t('core','insert'),
				'icon' => 'insert',
				'link' => array(
					'url' => 'views/' . $this->view . '/insert',
					'htmlOptions' => array('class'=>'icon'),
				),
				'visible' => $this->loadView()->getIsUpdatable(),
			),
			array(
				'label' => Yii::t('core','drop'),
				'icon' => 'delete',
				'link' => array(
					'url' => 'javascript:void(0)',
					'htmlOptions' => array('class'=>'icon', 'onclick'=>'viewGeneral.drop("'.$this->schema.'","'.$this->view.'");'),
				),
				'visible' => Yii::app()->user->privileges->checkTable($this->schema, $this->view, 'DROP'),
			),
		),
	));
	?>
</div>

<div id="dropViewDialog" title="<?php echo Yii::t('core', 'dropView'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropView'); ?>
	<ul></ul>
</div>

<div id="content-inner">
	<?php echo $content; ?>
</div>

<script type="text/javascript">
var schema = '<?php echo $this->schema; ?>';
var view = '<?php echo $this->view; ?>';
viewGeneral.setupDialogs();
breadCrumb.set([
	{
		icon: 'database',
		href: 'javascript:chive.goto(\'tables\')',
		text: schema
	},
	{
		icon: 'view',
		href: 'javascript:chive.goto(\'views/' + view + '/structure\')',
		text: view
	}
]);
sideBar.activate(1);
</script>