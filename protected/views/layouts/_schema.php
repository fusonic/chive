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
</script>

 $this->widget('TabMenu', array(
		'items'=>array(
			array(	'label'=> Yii::t('database','tables'),
					'icon'=>'table',
					'link'=>array(
						'url'=> '#tables',
						'htmlOptions'=> array('class'=>'icon'),
					),
					'visible'=>true,
			),
			array(	'label'=> Yii::t('database','views'),
					'icon'=>'view',
					'link'=>array(
						'url'=> '#views',
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
			array(	'label' => Yii::t('database', 'routines'),
					'icon' => 'procedure',
					'link' => array(
						'url' => '#routines',
						'htmlOptions' => array('class' => 'icon'),
					),
					'visible' => true,
			),
			array(	'label'=>Yii::t('action','drop'),
					'icon'=>'delete',
					'link'=>array(
						'url'=> 'javascript:void(0)',
						'htmlOptions'=> array('class'=>'icon', 'onclick'=>'schemaGeneral.dropSchema()'),
					),
					'visible'=>Yii::app()->user->privileges->checkSchema($this->schema, 'DROP'),
			),
		),
	));
?>

<div id="dropSchemaDialog" title=" echo Yii::t('core', 'confirm'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToDropSchema'); ?>
	<ul></ul>
</div>

<div>
	 echo $content; ?>
</div>

<script type="text/javascript">
schemaGeneral.setupDialogs();
breadCrumb.set([
	{
		icon: 'database',
		href: baseUrl + '/schema/' + schema,
		text: schema
	}
]);
</script>