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
<ul class="tabMenu">
	 foreach($items AS $item) {?>

		 echo CHtml::openTag('li', $item['htmlOptions']); ?>
			 echo CHtml::openTag('a', $item['a']['htmlOptions']); ?>
				 if($item['icon']) { ?><com:Icon size="16" name="{$item['icon']}" /> } ?>
				<span> echo $item['label']; ?></span>
			 echo CHtml::closeTag('a'); ?>
		 echo CHtml::closeTag('li'); ?>

	 } ?>
</ul>
<div class="clear"></div>