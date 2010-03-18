<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class Collation extends CActiveRecord
{
	
	const DEFAULT_CHARACTER_SET = 'utf8';
	const DEFAULT_COLLATION = 'utf8_general_ci';

	public $collationGroup;

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'COLLATIONS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return 'COLLATION_NAME';
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'characterSet' => array(self::BELONGS_TO, 'CharacterSet', 'CHARACTER_SET_NAME'),
		);
	}

	/**
	 * Returns the definition of the given collation.
	 *
	 * The definition contains charset, collation and language like this:
	 * cp1252 West European, Swedish (Case-Insensitive)
	 *
	 * @param	string				Collation name (e.g. utf8_general_ci)
	 * @return	string				Definition including charset, collation and language
	 */
	public static function getDefinition($collation, $showCharset = true)
	{
		$data = explode('_', $collation);
		$text = '';
		if($showCharset)
		{
			$text .= Yii::t('collation', $data[0]) . ', ';
		}
		if(count($data) > 1)
		{
			$text .= Yii::t('collation', $data[1]);
		}
		if(count($data) == 3)
		{
			$text .= ' (' . Yii::t('collation', $data[2]) . ')';
		}
		return $text;
	}

	/**
	 * Returns the character set of a collation.
	 *
	 * This is the content before the first underscore.
	 *
	 * @param	string				Collation name (e.g. utf8_general_ci)
	 * @return	string				Charset (e.g. utf8)
	 */
	public static function getCharacterSet($collation)
	{
		$data = explode('_', $collation);
		return $data[0];
	}

}