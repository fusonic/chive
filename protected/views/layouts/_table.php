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
var table = ' echo $this->table; ?>';
</script>

 $this->widget('TabMenu', array(
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
			array(	'label'=>Yii::t('core','export'),
					'icon'=>'save',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/export',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=>Yii::t('action','import'),
					'icon'=>'import',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/import',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			/*
			array(	'label'=>Yii::t('database','operations'),
					'icon'=>'operation',
					'link'=>array(
						'url'=> '#tables/' . $this->table . '/operations',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			*/
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

<div id="truncateTableDialog" title=" echo Yii::t('database', 'truncateTable'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToTruncateTable', array('{tableName}' => $this->table)); ?>
	<ul></ul>
</div>
<div id="dropTableDialog" title=" echo Yii::t('database', 'dropTable'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToDropTable', array('{tableName}' => $this->table)); ?>
	<ul></ul>
</div>

<div>
	 echo $content; ?>
</div>

<script type="text/javascript">
tableGeneral.setupDialogs();
breadCrumb.set([
	{
		icon: 'database',
		href: baseUrl + '/schema/' + schema,
		text: schema
	},
	{
		icon: 'table',
		href: baseUrl + '/schema/' + schema + '#tables/' + table + '/structure',
		text: table
	}
]);
</script>