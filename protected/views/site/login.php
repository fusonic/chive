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
 if (count($languages) > 0 ) {?>
	<div id="languageDialog" title=" echo Yii::t('core', 'chooseLanguage'); ?>">
		<table>
			<tr>
			 $i = 0; ?>
			 $languageCount = count($languages); ?>
			 foreach($languages AS $language) { ?>

				<td style="width: 150px;">
					<a href=" echo $language['url']; ?>" class="icon">
						<img src=" echo BASEURL . '/' . $language['icon']; ?>" alt="test" />
						<span> echo $language['label']; ?></span>
					</a>
				</td>

				 $i++; ?>
				 if ($i % 3 == 0 && $languageCount > $i) { ?>
					</tr><tr>
				 } ?>


			 } ?>
			</tr>
		</table>
		<span style="float:right; margin-top: 20px;">Help translating this project...</span>
	</div>
 } ?>

 if (count($themes) > 0 ) {?>
	<div id="themeDialog" title=" echo Yii::t('core', 'chooseTheme'); ?>">
		<table>
			<tr>
			 $i = 0; ?>
			 $themeCount = count($themes); ?>
			 foreach($themes AS $theme) { ?>

				<td style="width: 150px;">
					<a href=" echo $theme['url']; ?>" class="icon">
						<img src=" echo BASEURL . '/' . $theme['icon']; ?>" alt="test" />
						<span> echo $theme['label']; ?></span>
					</a>
				</td>

				 $i++; ?>
				 if ($i % 3 == 0 && $themeCount > $i) { ?>
					</tr><tr>
				 } ?>


			 } ?>
			</tr>
		</table>
	</div>
 } ?>

<div id="login">

	<div style="background: url('../images/logo-big.png') no-repeat 15px 0px; padding-bottom: 35px; height: 67px;"></div>

	 echo CHtml::errorSummary($form, '', ''); ?>

	<div id="loginform">
		 echo CHtml::form(); ?>
		<div class="formItems non-floated" style="text-align: left;">
			<div class="item row1">
				<div class="left">
					 echo CHtml::activeLabel($form,'host'); ?>
				</div>
				<div class="right">
					 echo CHtml::activeTextField($form, 'host', array('value'=>'localhost', 'class'=>'text')); ?>
				</div>
			</div>
			<div class="item row2">
				<div class="left" style="float: none;">
					 echo CHtml::activeLabel($form,'username'); ?>
				</div>
				<div class="right">
					 echo CHtml::activeTextField($form,'username', array('class'=>'text')) ?>
					 echo CHtml::error($form, 'username'); ?>
				</div>
			</div>
			<div class="item row1">
				<div class="left">
					 echo CHtml::activeLabel($form,'password'); ?>
				</div>
				<div class="right">
					 echo CHtml::activePasswordField($form,'password', array('class'=>'text')); ?>
				</div>
			</div>
		</div>

		<div class="buttons">
			<a class="icon button" href="javascript:void(0);" onclick="$('form').submit()">
				<com:Icon size="16" name="login" text="core.login" />
				<span> echo Yii::t('core', 'login'); ?></span>
			</a>
			<input type="submit" value=" echo Yii::t('core', 'login'); ?>" style="display: none" />
		</div>

		 echo CHtml::closeTag('form'); ?>
	</div>

</div>