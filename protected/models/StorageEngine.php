<?php

class StorageEngine
{

	const SUPPORTS_DELAY_KEY_WRITE = 0;
	const SUPPORTS_CHECKSUM = 1;
	const SUPPORTS_PACK_KEYS = 2;

	public static $engines = array(

		//							< OPTIONS             >
		// Engine					delkwr	chksum	pckkeys

		'MyISAM'		=> array(	true,	true,	true),
		'MEMORY'		=> array(	false,	false,	false),
		'InnoDB'		=> array(	false,	false,	false),
		'BLACKHOLE'		=> array(	false,	false,	false),
		'ARCHIVE'		=> array(	false,	false,	false),
		'CSV'			=> array(	false,	false,	false),

	);

	public static function check($engine, $property)
	{
		return self::$types[self::getFormattedName($engine)][$property];
	}

	public static function getFormattedName($engine)
	{
		switch(strtolower($engine))
		{
			case 'myisam':
				return 'MyISAM';
			case 'innodb':
				return 'InnoDB';
			default:
				return strtoupper($engine);
		}
	}

	public static function getSupportedEngines()
	{
		return array(
			'MyISAM' => Yii::t('storageEngines', 'MyISAM'),
			'MEMORY' => Yii::t('storageEngines', 'MEMORY'),
			'InnoDB' => Yii::t('storageEngines', 'InnoDB'),
			'BLACKHOLE' => Yii::t('storageEngines', 'BLACKHOLE'),
			'ARCHIVE' => Yii::t('storageEngines', 'ARCHIVE'),
			'CSV' => Yii::t('storageEngines', 'CSV'),
		);
	}

	public static function getPackKeyOptions()
	{
		return array(
			'DEFAULT' => Yii::t('core', 'default'),
			'1' => Yii::t('core', 'yes'),
			'0' => Yii::t('core', 'no'),
		);
	}

}

?>