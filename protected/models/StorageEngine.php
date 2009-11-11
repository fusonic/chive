<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
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


class StorageEngine extends SqlModel
{

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($class = __CLASS__)
	{
		return parent::model($class);
	}

	/**
	 * @see		SqlModel::getSql()
	 */
	protected function getSql()
	{
		return 'SHOW ENGINES';
	}

	/**
	 * @see		SqlModel::attributeNames()
	 */
	public function attributeNames()
	{
		return array(
			'Engine',
			'Support',
			'Comment',
		);
	}

	/**
	 * Returns wether DELAY_KEY_WRITE option is supported by storage engine.
	 *
	 * @return	bool				True, if supported. Else, if not.
	 */
	public function getSupportsDelayKeyWrite()
	{
		return self::check($this->Engine, self::SUPPORTS_DELAY_KEY_WRITE);
	}

	/**
	 * Returns wether CHECKSUM option is supported by storage engine.
	 *
	 * @return	bool				True, if supported. Else, if not.
	 */
	public function getSupportsChecksum()
	{
		return self::check($this->Engine, self::SUPPORTS_CHECKSUM);
	}

	/**
	 * Returns wether PACK_KEYS option is supported by storage engine.
	 *
	 * @return	bool				True, if supported. Else, if not.
	 */
	public function getSupportsPackKeys()
	{
		return self::check($this->Engine, self::SUPPORTS_PACK_KEYS);
	}




	/*
	 * static things ...
	 */

	const SUPPORTS_DELAY_KEY_WRITE = 0;
	const SUPPORTS_CHECKSUM = 1;
	const SUPPORTS_PACK_KEYS = 2;
	const SUPPORTS_FOREIGN_KEYS = 3;

	public static $engines = array(

	//							< OPTIONS             >
	// Engine					delkwr	chksum	pckkeys	fkeys

		'MyISAM'		=> array(	true,	true,	true,	false),
		'MEMORY'		=> array(	false,	false,	false,	false),
		'InnoDB'		=> array(	false,	false,	false,	true),
		'BerkeleyDB'	=> array(	false,	false,	false,	false),
		'BLACKHOLE'		=> array(	false,	false,	false,	false),
		'EXAMPLE'		=> array(	false,	false,	false,	false),
		'ARCHIVE'		=> array(	false,	false,	false,	false),
		'CSV'			=> array(	false,	false,	false,	false),
		'ndbcluster'	=> array(	false,	false,	false,	false),
		'FEDERATED'		=> array(	false,	false,	false,	false),
		'MRG_MYISAM'	=> array(	false,	false,	false,	false),
		'ISAM'			=> array(	false,	false,	false,	false),

	);

	/**
	 * Checks a specific property for a specific storage engine.
	 *
	 * @param	string				Storage engine
	 * @param	int					Property Index (use constants to get them)
	 * @return	bool				True, if supported. Else, if not.
	 */
	public static function check($engine, $property)
	{
		return self::$engines[self::getFormattedName($engine)][$property];
	}

	/**
	 * Returns the formatted name of a storage engine.
	 *
	 * @param	string				Storage engine name
	 * @return	string				Formatted name
	 */
	public static function getFormattedName($engine)
	{
		switch(strtolower($engine))
		{
			case 'myisam':
				return 'MyISAM';
			case 'innodb':
				return 'InnoDB';
			case 'berkeleydb':
				return 'BerkeleyDB';
			case 'ndbcluster':
				return 'ndbcluster';
			default:
				return strtoupper($engine);
		}
	}

	/**
	 * Returns all storage engines which are supported by the current server.
	 *
	 * @return	array				Array of supported StorageEngine objects
	 */
	public static function getSupportedEngines()
	{
		return StorageEngine::model()->findAllByAttributes(array(
			'Support' => array('YES', 'DEFAULT'),
		));
	}

	/**
	 * Returns the available settings for the PACK_KEY option to use in selects.
	 *
	 * @return	array				All settings for the PACK_KEY option
	 */
	public static function getPackKeyOptions()
	{
		return array(
			'DEFAULT' => Yii::t('core', 'default'),
			'1' => Yii::t('core', 'yes'),
			'0' => Yii::t('core', 'no'),
		);
	}
}