<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */
<script type="text/javascript">
var schema = ' echo $this->schema; ?>';
var table = ' echo $this->view; ?>';

sideBar.accordion('activate', 1);

</script>

 $this->widget('TabMenu', array(
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

<div id="dropViewDialog" title=" echo Yii::t('database', 'dropView'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToDropView'); ?>
	<ul></ul>
</div>

<div>
	 echo $content; ?>
</div>

<script type="text/javascript">
viewGeneral.setupDialogs();
</script>